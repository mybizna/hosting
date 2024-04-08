<?php

namespace App\Services;

use App\Mailers\Mailer;
use App\Models\Tracker;
use Illuminate\Support\Facades\Http;

class Whmcs
{
    public function createCustomer($user)
    {
        $tracker = Tracker::where('partner_id', $user->id)->first();
        $clients = $this->searchClients($user->email);

        if (intval($clients['totalresults']) === 0) {
            $address = optional($user->profile)->town . ' ' . optional($user->profile)->country->name ?: 'Nairobi, Kenya';

            $clientArr = [
                'action' => 'AddClient',
                'firstname' => $user->first_name,
                'lastname' => $user->last_name,
                'email' => $user->email,
                'address1' => optional($user->profile)->address ?: $address,
                'city' => optional($user->profile)->town ?: 'Nairobi',
                'state' => 'ST',
                'postcode' => '00100',
                'country' => optional($user->profile)->country->code ?: 'KE',
                'phonenumber' => optional($user->profile)->phone ?: '0000000000',
                'password2' => '<>' . $user->username . '<>',
                'clientip' => $tracker ? $tracker->ip_address : '95.217.83.244',
                'responsetype' => 'json',
            ];

            $result = $this->whmcsCall($clientArr);
            $clients = $this->searchClients($user->email);
        }

        return $clients['clients']['client'][0];
    }

    // Rest of the methods go here...
    public function createSsoToken($domain)
    {
        $products = $this->searchClientProduct($domain->name);

        if ($products['result'] === 'success' && intval($products['totalresults']) > 0) {
            $serviceId = $products['products']['product'][0]['id'];
            $clientId = $products['products']['product'][0]['clientid'];

            $searchArr = [
                'action' => 'CreateSsoToken',
                'client_id' => $clientId,
                'service_id' => $serviceId,
                'destination' => 'clientarea:product_details',
                'stats' => true,
                'responsetype' => 'json',
            ];

            $token = $this->whmcsCall($searchArr);

            $token['service_id'] = $serviceId;

            return $token;
        }

        return false;
    }

    // Rest of the methods...

    public function updateCustomer($user)
    {
        $tracker = Tracker::where('partner_id', $user->id)->first();

        $clientArr = [
            'action' => 'UpdateClient',
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'email' => $user->email,
            'address1' => $user->profile->address ?? $user->profile->town . ' ' . $user->profile->country->name,
            'city' => $user->profile->town ?? 'Nairobi',
            'state' => 'ST',
            'postcode' => '00100',
            'country' => $user->profile->country->code ?? 'KE',
            'phonenumber' => $user->profile->phone ?? '0000000000',
            'password2' => '<>' . $user->username . '<>',
            'clientip' => $tracker ? $tracker->ip_address : '95.217.83.244',
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($clientArr);
    }

    public function createOrder($hosting, $isSubdomain = false)
    {
        if (!$hosting->user) {
            return false;
        }

        $globalPreferences = global_preferences_registry::manager();
        $whmcsProductId = $globalPreferences['hosting__whmcs_product_id'];

        $clients = $this->searchClients($hosting->user->email);

        $order = false;

        if (intval($clients['totalresults']) > 0) {
            $tmpClient = $clients['clients']['client'][0];
            $username = $hosting->user->username;
            $password = '<>' . $hosting->user->username . '<>';

            $serialized = 'a:3:{i:1;s:9:"WordPress";i:2;s:' . strlen($username) . ':"' . $username . '";i:3;s:' . strlen($password) . ':"' . $password . '";}';

            $orderArr = [
                'action' => 'AddOrder',
                'clientid' => $tmpClient['id'],
                'pid[0]' => '11',
                'domain[0]' => $hosting->domain->name,
                'customfields[0]' => base64_encode($serialized),
                'paymentmethod' => 'mailin',
                'responsetype' => 'json',
            ];

            if ($isSubdomain) {
                $orderArr['domaintype[0]'] = 'register';
                $orderArr['regperiod[0]'] = '1';
            }

            $order = $this->whmcsCall($orderArr);

            $hosting->whmcs_order_id = $order['orderid'];
            $hosting->is_in_whmcs = true;
            $hosting->save();

            if ($order['orderid']) {
                $now = now();

                $invoicePayment = [
                    'action' => 'AddInvoicePayment',
                    'invoiceid' => $order['invoiceid'],
                    'transid' => rand(100000000, 999999999),
                    'gateway' => 'mailin',
                    'date' => $now->format("Y-m-d H:i:s"),
                    'responsetype' => 'json',
                ];

                $order['invoicepayment'] = $this->whmcsCall($invoicePayment);

                $acceptOrder = [
                    'action' => 'AcceptOrder',
                    'orderid' => $order['orderid'],
                    'autosetup' => '1',
                    'sendemail' => '0',
                    'responsetype' => 'json',
                ];

                $order['acceptorder'] = $this->whmcsCall($acceptOrder);
            }
        }

        return $order;
    }
// Rest of the methods...

    public function getClientId($email)
    {
        $searchArr = [
            'action' => 'GetClients',
            'email' => $email,
            'responsetype' => 'json',
        ];

        $clients = $this->whmcsCall($searchArr);

        return $clients['clients']['client'][0]['id'] ?? null;
    }

    public function getUserByDomain($domain)
    {
        $searchArr = [
            'action' => 'GetClientsDomains',
            'domain' => $domain,
            'responsetype' => 'json',
        ];

        $domains = $this->whmcsCall($searchArr);

        return $domains['totalresults'] > 0 ? $domains['domains']['domain'][0]['userid'] : false;
    }

    public function getRegisterDomain($domain)
    {
        if (!$domain->user) {
            return false;
        }

        $clients = $this->searchClients($domain->user->email);

        if (intval($clients['totalresults']) > 0) {
            $tmpClient = $clients['clients']['client'][0];
            // Additional logic specific to getRegisterDomain method
            // ...
        }

        // Additional logic specific to getRegisterDomain method
        // ...

        return $registerDomainData; // Return the required data
    }

    public function updateServer($domain, $serverId)
    {
        $isSuccessful = false;

        $searchArr = [
            'action' => 'GetClientsProducts',
            'domain' => $domain,
            'responsetype' => 'json',
        ];

        $products = $this->whmcsCall($searchArr);

        if (intval($products['totalresults'])) {
            $tmpProduct = $products['products']['product'][0];

            $updateArr = [
                'action' => 'UpdateClientProduct',
                'serviceid' => $tmpProduct['id'],
                'serverid' => $serverId,
                'responsetype' => 'json',
            ];

            $results = $this->whmcsCall($updateArr);

            if ($results['result'] === 'success') {
                $isSuccessful = true;
            }
        }

        return $isSuccessful;
    }

    public function updateNameservers($domainName, $nameserver1, $nameserver2, $nameserver3, $nameserver4)
    {
        $updateArr = [
            'action' => 'DomainUpdateNameservers',
            'domain' => $domainName,
            'ns1' => $nameserver1,
            'ns2' => $nameserver2,
            'ns3' => $nameserver3,
            'ns4' => $nameserver4,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($updateArr);
    }

    public function getDomainEmail($domain)
    {
        $searchArr = [
            'action' => 'GetClientsDomains',
            'domain' => $domain,
            'responsetype' => 'json',
        ];

        $domains = $this->whmcsCall($searchArr);

        if ($domains['result'] == 'success' && intval($domains['totalresults']) > 0) {
            $userId = $domains['domains']['domain'][0]['userid'];

            $searchArr = [
                'action' => 'GetClientsDetails',
                'clientid' => $userId,
                'responsetype' => 'json',
            ];

            $client = $this->whmcsCall($searchArr);

            if ($client['result'] == 'success') {
                return $client['client']['email'];
            }
        }

        return false;
    }

    public function getUserDomains($email)
    {
        $clients = $this->searchClients($email);
        $domainsList = [];

        if ($clients['result'] == 'success' && intval($clients['totalresults']) > 0) {
            foreach ($clients['clients']['client'] as $client) {
                $domains = $this->searchClientDomains($client['id']);

                if (intval($domains['totalresults']) > 0) {
                    $domainsList = array_merge($domainsList, $domains['domains']['domain']);
                }
            }
        }

        return $domainsList;
    }

    public function getUserProducts($email)
    {
        $clients = $this->searchClients($email);
        $productsList = [];

        if ($clients['result'] == 'success' && intval($clients['totalresults']) > 0) {
            foreach ($clients['clients']['client'] as $client) {
                $products = $this->searchClientProducts($client['id']);

                if (intval($products['totalresults']) > 0) {
                    $productsList = array_merge($productsList, $products['products']['product']);
                }
            }
        }

        return $productsList;
    }

    public function addCreditDeposits($clientId, $amount, $description)
    {
        $credit = [
            'action' => 'AddCredit',
            'clientid' => $clientId,
            'description' => $description,
            'amount' => $amount,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($credit);
    }
    public function addCredit($clientId, $amount, $description)
    {
        $credit = [
            'action' => 'AddCredit',
            'clientid' => $clientId,
            'description' => $description,
            'amount' => $amount,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($credit);
    }
    public function getOrders($orderId)
    {
        $searchArr = [
            'action' => 'GetOrders',
            'id' => $orderId,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function getInvoices($userId)
    {
        $searchArr = [
            'action' => 'GetInvoices',
            'userid' => $userId,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }
    public function getInvoice($invoiceId)
    {
        $searchArr = [
            'action' => 'GetInvoice',
            'invoiceid' => $invoiceId,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }
    public function getClientsDetails($clientId)
    {
        $searchArr = [
            'action' => 'GetClientsDetails',
            'clientid' => $clientId,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function searchClients($email)
    {
        $searchArr = [
            'action' => 'GetClients',
            'search' => $email,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function fetchClients($offset, $limit)
    {
        $searchArr = [
            'action' => 'GetClients',
            'limitstart' => $offset,
            'limitnum' => $limit,
            'sorting' => 'ASC',
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function searchDomain($domain)
    {
        $searchArr = [
            'action' => 'GetClients',
            'search' => $domain,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function searchClientDomain($domain)
    {
        $searchArr = [
            'action' => 'GetClientsDomains',
            'domain' => $domain,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function searchClientProduct($domain)
    {
        $searchArr = [
            'action' => 'GetClientsProducts',
            'domain' => $domain,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function searchClientProducts($clientId)
    {
        $searchArr = [
            'action' => 'GetClientsProducts',
            'clientid' => $clientId,
            'stats' => true,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function moduleTerminate($productId)
    {
        $searchArr = [
            'action' => 'ModuleTerminate',
            'serviceid' => $productId,
            'stats' => true,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function searchClientDomains($clientId)
    {
        $searchArr = [
            'action' => 'GetClientsDomains',
            'clientid' => $clientId,
            'stats' => true,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    public function deleteClient($clientId)
    {
        $searchArr = [
            'action' => 'DeleteClient',
            'clientid' => $clientId,
            'deleteusers' => false,
            'deletetransactions' => true,
            'stats' => true,
            'responsetype' => 'json',
        ];

        return $this->whmcsCall($searchArr);
    }

    protected function whmcsCall($params)
    {
        $mailer = new Mailer(); // Assuming you've implemented Mailer class
        $globalPreferences = config('global.preferences'); // Load preferences from config

        $whmcsServerUrl = $globalPreferences['hosting__whmcs_server_url'];

        $params['identifier'] = $globalPreferences['hosting__whmcs_server_api_identifier'];
        $params['secret'] = $globalPreferences['hosting__whmcs_server_api_secret'];
        $params['accesskey'] = $globalPreferences['hosting__whmcs_api_access_key'];

        $response = Http::get($whmcsServerUrl . 'includes/api.php', $params);

        // You can add mail sending here if needed
        // try {
        //     $mailer->sendMail('whmcsCall', 'whmcsCall ' . '<br><br>'  . $response->body() . '<br><br>' . json_encode($params), 'dedanirungu@gmail.com');
        // } catch (\Exception $e) {
        //     // Handle exception
        // }

        return $response->json();
    }

    public function expiredClients($params)
    {
        $globalPreferences = config('global.preferences');
        $whmcsServerUrl = $globalPreferences['hosting__whmcs_server_url'];

        $params['identifier'] = $globalPreferences['hosting__whmcs_server_api_identifier'];
        $params['secret'] = $globalPreferences['hosting__whmcs_server_api_secret'];
        $params['accesskey'] = $globalPreferences['hosting__whmcs_api_access_key'];

        $response = Http::get($whmcsServerUrl . 'custom_fetchclients.php', $params);

        return $response->json();
    }
}
