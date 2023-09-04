<?php

namespace PaymentPackage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Paymob\Library\Paymob;

class PaymentController extends Controller
{
    public function process()
    {
        try {
            // Initialize Paymob
            $paymob = new Paymob();
            $conf = [
                'apiKey' => config('payment-package.api_key'),
                'secKey' => config('payment-package.secret_key'),
                'pubKey' => config('payment-package.public_key'),
                'integration_id' => config('payment-package.payment_method_integration_id'),
            ];

            $cents = 100; // 100 for all countries and 1000 for Oman
            $billing = [
                "email" => 'email@email.com',
                "first_name" => 'fname',
                "last_name" => 'lname',
                "street" => 'street',
                "phone_number" => '1234567890',
                "city" => 'city',
                "country" => 'country',
                "state" => 'state',
                "postal_code" => '12345',
            ];

            $data = [
                "amount" => 10 * $cents,
                "currency" => 'EGP',
                "payment_methods" => array(1234567),
                // replace this id 1234567 with your integration ID(s)
                "billing_data" => $billing,
                "extras" => ["merchant_intention_id" => '123_' . time()],
                "special_reference" => '123_' . time()
            ];

            $status = $paymob->createIntention($conf['secKey'], $data);
            $countryCode = $paymob->getCountryCode($conf['secKey']);
            $apiUrl = $paymob->getApiUrl($countryCode);
            $cs = $status['cs'];

            $to = $apiUrl . "unifiedcheckout/?publicKey=" . $conf['pubKey'] . "&clientSecret=$cs";
            //echo "Pay using the URL $to";
            return redirect($to);

            exit;
            // Configure Paymob settings
            // Get authentication token and integration IDs
            $authData = $paymob->authToken($conf);

            // Call other Paymob methods and handle payment logic
            // For example: createIntention
            $data = [
                // Payment data
            ];
            $intentionStatus = $paymob->createIntention($conf['secKey'], $data);

            if ($intentionStatus['success']) {
                // Redirect to success page
                return view('payment::success');
            } else {
                // Redirect to failure page with error message
                return view('payment::failure', ['error' => $intentionStatus['message']]);
            }
        } catch (Exception $e) {
            // Handle exceptions
            return view('payment::failure', ['error' => $e->getMessage()]);
        }
    }

    public function success()
    {
        return view('payment::success');
    }

    public function failure(Request $request)
    {
        $error = $request->input('error');
        return view('vendor.payment.failure', ['error' => $error]);
    }
}