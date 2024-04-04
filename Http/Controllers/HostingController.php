<?php
use Modules\Domain\Classes\DomainProcessor;
use Modules\Hosting\Classes\HostingProcessor;
use Modules\Hosting\Classes\Whmcs;
use App\Models\User;
use Modules\Account\Classes\Payment;
use Modules\Domain\Classes\Domain;
use Modules\Hosting\Classes\Hosting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class HostingController extends Controller
{
    public function index(Request $request)
    {
        $context = [
            'title' => "Hostings",
        ];

        return view('index', $context);
    }

    public function removeDuplicates()
    {
        $domainDuplicates = Domain::select('name')
            ->selectRaw('COUNT(name) as domain_name_count')
            ->having('domain_name_count', '>', 1)
            ->groupBy('name')
            ->get();

        foreach ($domainDuplicates as $data) {
            $name = $data['name'];
            $listings = Domain::where('name', $name)
                ->orderByDesc('expiry_date')
                ->skip(1)
                ->get();

            foreach ($listings as $item) {
                $hostingList = Hosting::where('domain_id', $item->id)->get();

                foreach ($hostingList as $host) {
                    $host->delete();
                }

                $item->delete();
            }
        }

        $hostingDuplicates = Hosting::select('domain_id')
            ->selectRaw('COUNT(domain_id) as domain_id_count')
            ->having('domain_id_count', '>', 1)
            ->groupBy('domain_id')
            ->get();

        foreach ($hostingDuplicates as $data) {
            $domainId = $data['domain_id'];
            $listings = Hosting::where('domain_id', $domainId)
                ->orderByDesc('expiry_date')
                ->skip(1)
                ->get();

            foreach ($listings as $item) {
                $item->delete();
            }
        }

        return redirect('manage_hosting_list');
    }

    public function gifwalletapi(Request $request)
    {
        $context = [
            'status' => "Error",
            'transid' => 0,
            'message' => 'Error'
        ];

        $email = $request->input('email');

        if (!$email) {
            return JsonResponse::create($context);
        }

        $payment = Payment::where('user.email', $email)->first();

        if (!$payment) {
            return JsonResponse::create($context);
        }

        $context = [
            'status' => "Success",
            'transid' => $payment->id,
            'message' => 'Successful'
        ];

        return JsonResponse::create($context);
    }

    public function verifykey(Request $request)
    {
        $accesskey = $request->input('accesskey');
        $current_user = auth()->user();

        $storedAccesskey = Cache::get('accesskey' . $current_user->id);

        $context = [
            'status' => 0
        ];

        if ($accesskey == $storedAccesskey) {
            $context['status'] = 1;
        }

        return JsonResponse::create($context);
    }

    public function createssotoken(Request $request)
    {
        $whmcs = new Whmcs();
        $accesskey = Str::uuid()->toString();
        $current_user = auth()->user();
        $domainId = $request->input('domain_id');
        $domain = Domain::find($domainId);

        if (auth()->user()->isSuperAdmin() || $current_user->id == $domain->user_id) {
            $ssoToken = $whmcs->createSsoToken($domain);
            Session::flash('success', 'Autologin is Successfully');
            return Redirect::away($ssoToken['redirect_url']);
        } else {
            Session::flash('error', 'Please Login and Try autologin again.');
            return redirect('home');
        }
    }

    public function accesspass(Request $request)
    {
        $whmcs = new Whmcs();
        $accesskey = Str::uuid()->toString();
        $current_user = auth()->user();
        $domainStr = $request->input('domain');
        $tmpDomainStr = parse_url($domainStr, PHP_URL_HOST);
        $domainStr = $tmpDomainStr ?: $domainStr;

        if ($current_user->isSuperAdmin()) {
            Cache::put('accesskey' . $current_user->id, $accesskey, 60);
            Session::flash('success', 'Autologin is Successfully');
            return Redirect::away('https://' . $domainStr . '/wp-autologin.php?accesskey=' . $accesskey);
        }

        if ($current_user->id) {
            $domain = parse_url($domainStr, PHP_URL_HOST);
            $domains = $whmcs->getUserDomains($current_user->email);
            $domainCount = Domain::where('name', $domainStr)
                ->where('user_id', $current_user->id)
                ->count();

            if ($domainCount || in_array($domain, $domains)) {
                Cache::put('accesskey' . $current_user->id, $accesskey, 60);
                Session::flash('success', 'Autologin is Successfully');
                return Redirect::away('https://' . $domainStr . '/wp-autologin.php?accesskey=' . $accesskey);
            } else {
                Session::flash('error', 'The Domain ' . $domainStr . ' was not Found in your domain list[' . implode($domains) . ']. Please Contact Support. ');
            }
        } else {
            Session::flash('error', 'Please Login and Try autologin again.');
        }

        return redirect('home');
    }

    public function whmcsorders(Request $request)
    {
        $hostingProcessor = new HostingProcessor();
        $context = $hostingProcessor->syncDomainPurchase();
        return redirect('home');
    }

    public function completewhmcsorders($pk)
    {
        $nextTo = request()->get('next_to', 'home');
        $hosting = Hosting::find($pk);
        $hosting->is_in_whmcs = true;
        $hosting->save();
        return redirect($nextTo);
    }

    public function purchasewhmcsorders($pk)
    {
        $nextTo = request()->get('next_to', 'home');
        $hostingProcessor = new HostingProcessor();
        $hosting = Hosting::find($pk);
        $hosting->status = true;
        $hosting->paid = true;
        $hosting->completed = true;

        if (!$hosting->expiry_date) {
            $expiryDate = $hostingProcessor->getExpiryDate($hosting->expiry_date, $hosting->package->no_of_days);
            $hosting->expiry_date = $expiryDate;
            $hosting->last_upgrade_date = now();

            if (!$hosting->upgrade_date) {
                $hosting->upgrade_date = now();
            }

            if ($hosting->is_new) {
                $hosting->upgrade_date = now();
            }
        }

        $hosting->save();
        $context = $hostingProcessor->syncDomainPurchase($pk);
        return redirect($nextTo);
    }

    public function userHostingRenew($pk)
    {
        $hostingProcessor = new HostingProcessor();
        $hosting = Hosting::find($pk);
        $context = $hostingProcessor->prepareForRenewContext($hosting);
        return view('user.hosting_payment', $context);
    }

    public function userHostingPayment($pk)
    {
        $hostingProcessor = new HostingProcessor();
        $hosting = Hosting::find($pk);
        $context = $hostingProcessor->prepareForNewContext($hosting);
        return view('user.hosting_payment', $context);
    }
}
