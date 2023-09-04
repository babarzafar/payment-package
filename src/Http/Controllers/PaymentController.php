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

            // Configure Paymob settings
            $conf = [
                'apiKey' => config('payment-package.api_key'),
                'secKey' => config('payment-package.secret_key'),
                'pubKey' => config('payment-package.public_key'),
                'integration_id' => config('payment-package.payment_method_integration_id'),
            ];

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