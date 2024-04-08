<?php 


namespace Modules\Hosting\Classes;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Hosting\Entities\Hosting;
use Modules\Hosting\Entities\Package;
use Modules\Domain\Entities\Domain;
use Modules\Affiliate\Entities\Affiliate;
use Modules\Affiliate\Entities\Package as AffiliatePackage;
use Modules\Account\Entities\Source;
use App\Models\User;
use App\Classes\Whmcs;
use App\Classes\Mailer;
use App\Classes\UpgradeAccount;
use App\Classes\PaymentProcessor;
use App\Classes\DomainProcessor;
use DynamicPreferences;

class HostingProcessor
{
    public function saveHostingFromInput($request)
    {
        $domainProcessor = new DomainProcessor();

        $packageId = $request->input('package_id') ?: $request->input('package_id');
        $userId = $request->input('partner_id') ?: $request->input('partner_id');

        $user = User::findOrFail($userId);

        $package = null;
        if ($packageId) {
            $package = Package::find($packageId);
        } else {
            $package = Package::where('price', '>', 0)->first();
        }

        $domain = $domainProcessor->saveDomainFromInput($request);

        $hosting = new Hosting([
            'domain_id' => $domain->id,
            'partner_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $package->price,
        ]);

        $hosting->save();

        return $hosting;
    }

    public function prepareForRenewContext($hosting, $nextTo = 'user_hosting_list')
    {
        $paymentProcessor = new PaymentProcessor();

        $paymentDict = [
            "partner_id" => $hosting->partner_id,
            "app_name" => 'hosting',
            "model_name" => 'Hosting',
            "next_to" => $nextTo,
            "type" => 'purchase-hosting',
            "is_new" => 0,
            "description" => 'Renew Package[' . $hosting->package->title . '] for (' . $hosting->domain->name . ') worth ' . $hosting->package->price,
            "quantity" => 1,
            "amount" => $hosting->package->price,
            "source_ident" => $hosting->id,
            "source_package_ident" => $hosting->package_id,
            "items" => [],
        ];

        $context = $paymentProcessor->prepareContext($paymentDict);
        $context['title'] = 'Hosting Payment';
        $context['hosting'] = $hosting;

        // Save payment
        $hosting->payment()->associate($context['payment']);
        $hosting->save();

        return $context;
    }

    public function prepareForNewContext($hosting, $nextTo = 'user_hosting_list')
    {
        $paymentProcessor = new PaymentProcessor();
        $items = [];

        if (!$hosting->domain->paid) {
            $setupFee = $hosting->package->setup_fee ?? 0;
            if ($hosting->domain->price->price) {
                $items = [
                    [
                        "app_name" => 'domain',
                        "model_name" => 'Domain',
                        "source_ident" => $hosting->domain->id,
                        "source_package_ident" => $hosting->domain->price->id,
                        "amount" => $hosting->domain->price->price,
                        "description" => 'Purchase Domain (' . $hosting->domain->name . ') ',
                        "quantity" => 1,
                    ],
                    [
                        "app_name" => 'hosting',
                        "model_name" => 'Hosting',
                        "source_ident" => $hosting->id,
                        "source_package_ident" => $hosting->package_id,
                        "amount" => floatval($hosting->package->price) + floatval($setupFee),
                        "description" => 'One Year Hosting for (' . $hosting->domain->name . ') ',
                        "quantity" => 1,
                    ],
                ];
            } else {
                $items = [
                    [
                        "app_name" => 'hosting',
                        "model_name" => 'Hosting',
                        "source_ident" => $hosting->id,
                        "source_package_ident" => $hosting->package_id,
                        "amount" => floatval($hosting->package->price) + floatval($setupFee),
                        "description" => 'One Year Hosting for (' . $hosting->domain->name . ') ',
                        "quantity" => 1,
                    ],
                ];
            }
        }

        $paymentDict = [
            "partner_id" => $hosting->partner_id,
            "app_name" => 'hosting',
            "model_name" => 'Hosting',
            "next_to" => $nextTo,
            "type" => 'purchase-hosting',
            "is_new" => 1,
            "description" => 'Purchase Package[' . $hosting->package->title . '] for (' . $hosting->domain->name . ') ',
            "quantity" => 1,
            "amount" => $hosting->package->price,
            "source_ident" => $hosting->id,
            "source_package_ident" => $hosting->package_id,
            "items" => $items,
        ];

        $context = $paymentProcessor->prepareContext($paymentDict);
        $context['title'] = 'Hosting Payment';
        $context['hosting'] = $hosting;

        // Save payment
        $hosting->payment()->associate($context['payment']);
        $hosting->save();

        return $context;
    }

    public function updateRecord($hosting, $payment)
    {
        $this->updateHosting($hosting);
        // $this->updateAffiliate($hosting, $payment);
        $this->syncDomainPurchase($hosting->id);
    }

    public function updateHosting($hosting)
    {
        $expiryDate = $this->getExpiryDate($hosting->expiry_date, $hosting->package->no_of_days);

        $hosting->status = true;
        $hosting->paid = true;
        $hosting->completed = true;
        $hosting->is_new = false;
        $hosting->expiry_date = $expiryDate;
        $hosting->last_upgrade_date = now();

        if (!$hosting->upgrade_date) {
            $hosting->upgrade_date = now();
        }

        if ($hosting->is_new) {
            $hosting->upgrade_date = now();
        }

        $hosting->save();

        $domain = Domain::find($hosting->domain_id);
        $domain->status = true;
        $domain->paid = true;
        $domain->completed = true;
        $domain->is_new = false;
        $domain->expiry_date = $expiryDate;
        $domain->last_upgrade_date = now();

        if (!$domain->upgrade_date) {
            $domain->upgrade_date = now();
        }

        if ($domain->is_new) {
            $domain->upgrade_date = now();
        }

        $domain->save();
    }

    public function getExpiryDate($expiryDate, $noOfDays = 30)
    {
        $noOfDays = $noOfDays ?: 30;
        $d = Carbon::now()->addDays($noOfDays);

        if (!$expiryDate) {
            $expiryDate = Carbon::now()->addDays($noOfDays);
        } else {
            $expiryDate = Carbon::parse($expiryDate);

            if ($expiryDate > Carbon::now()) {
                $expiryDate = $expiryDate->addDays($noOfDays);
            } else {
                $expiryDate = Carbon::now()->addDays($noOfDays);
            }
        }

        return $expiryDate;
    }

    public function getDefaultPackage($sourceIdent = '')
    {
        if ($sourceIdent) {
            $package = AffiliatePackage::where('source_ident', $sourceIdent)->first();
            $package = $package ?: AffiliatePackage::where('paid_default', 1)->first();
            $package = $package ?: AffiliatePackage::first();

            return $package;
        } else {
            $package = AffiliatePackage::where('paid_default', 1)->first();
            $package = $package ?: AffiliatePackage::first();

            return $package;
        }
    }

    public function syncDomainPurchase($id = null)
    {
        $whmcs = new Whmcs();
        $mailer = new Mailer();
        $disablePurchase = DynamicPreferences::get('hosting__whmcs_disable_purchase');

        $isSubdomain = true;

        if (!$disablePurchase) {
            Hosting::whereNull('domain_id')->delete();

            if ($id) {
                $hostings = Hosting::where('id', $id)->take(100)->get();
            } else {
                $hostings = Hosting::where([
                    ['paid', '=', false],
                    ['is_registered', '=', false]
                ])->take(100)->get();
            }

            foreach ($hostings as $hosting) {
                if (!$hosting->domain) {
                    $hosting->delete();
                    continue;
                }

                $domainRegistered = false;

                if (!$isSubdomain) {
                    $domainDict = $whmcs->domainWhois($hosting->domain->name);
                    $domainRegistered = $domainDict['status'] !== 'available';
                }

                if (!$domainRegistered) {
                    try {
                        $whmcs->createCustomer($hosting->user);
                        $whmcs->createOrder($hosting, $isSubdomain);
                    } catch (\Exception $e) {
                    }
                }

                $hosting->paid = true;
                $hosting->is_registered = true;
                $hosting->save();
            }
        }

        return true;
    }

    public function syncExpiryPaidStatus()
    {
        $activeHostingList = $this->getActiveToSync();

        foreach ($activeHostingList as $hosting) {
            $hosting->paid = true;
            $hosting->status = true;
            $hosting->complete = true;
            $hosting->successful = true;
            $hosting->save();

            if (!$hosting->domain) {
                $hosting->delete();
            }
        }

        $inactiveHostingList = $this->getInActiveToSync();

        foreach ($inactiveHostingList as $hosting) {
            $hosting->paid = false;
            $hosting->status = false;
            $hosting->complete = false;
            $hosting->successful = false;
            $hosting->save();

            if (!$hosting->domain) {
                $hosting->delete();
            }
        }
    }

    public function getActiveToSync()
    {
        $today = Carbon::today();

        return Hosting::where([
            ['paid', '=', false],
            ['expiry_date', '>', $today]
        ])->take(100)->get();
    }

    public function getInActiveToSync()
    {
        $today = Carbon::today();

        return Hosting::where([
            ['paid', '=', true],
            ['expiry_date', '<', $today]
        ])->take(100)->get();
    }
}
