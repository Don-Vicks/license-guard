<?php

namespace App\Http\Controllers;

use App\Mail\LicensePaid;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\License;
use App\Models\LicenseType;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;

class Checkout extends Controller
{
    public function checkout(Request $request){
        $user = auth()->user();
        $license = License::where('id', $request->id)->first();
        //dd($license, $request->id);
        $licenseType = LicenseType::where('id', $license->type_id)->first();
        $amount = $request->plan;
        $checkout = env('CHECKOUT');
       // $planId = $request->planId;

        $data = [
            'tx_ref' => Str::random(16), // Replace with unique transaction reference
            'amount' => $licenseType->amount,
            'currency' => 'NGN',
            'redirect_url' => route('checkout.verify'),
            'meta' => [
                'license_id' => $request->id,
            ],
            'customer' => [
                'email' => $user->email,
                'phonenumber' => $user->phone_number,
                'name' => $user->name,
                'license_id' => $request->id,
               // 'plan_id' => $planId
            ],
            'customizations' => [
                'title' => 'License Payment',
                'logo' => 'https://teendev.dev/wp-content/uploads/2023/04/DARK-BLUE-e1682352569915.png'
            ]
        ];
        $paydata = [
            "email" => $user->email,
            "amount" => $licenseType->amount * 100,
            'callback_url' => route('checkout.verify'),
            'metadata' => [
                'license_id' => $request->id,
            ],
        ];

        if($checkout == 'flutterwave'){
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . env('FLW_SECRET_KEY')
                ])->post('https://api.flutterwave.com/v3/payments', $data);

                // Handle successful response
                return redirect($response->json()['data']['link']); // Redirect to payment page
            } catch (\Throwable $exception) {
                // Handle payment error
                // Log::error($exception->getMessage());
                return back()->with('error', $exception->getMessage()); // Redirect back with error message
            }
        } else {
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . env('PAYSTACK_KEY')
                ])->post('https://api.paystack.co/transaction/initialize', $paydata);

                // Handle successful response
return redirect($response->json()['data']['authorization_url']);
            } catch (\Throwable $exception) {
                // Handle payment error
                Log::error($exception->getMessage());
                Log::error($exception);
                //return back()->with('error', $exception->getMessage()); // Redirect back with error message
            }
        }
    }

public function verify(Request $request)
{
    $status = $request->status ?? null;
    $txref = $request->tx_ref ?? $request->reference;
    $trxid = $request->transaction_id ?? $request->trxref;
    $checkout = env('CHECKOUT');

    if ($checkout == 'flutterwave') {
        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => 'Bearer ' . env('FLW_SECRET_KEY'),
            'Content-Type' => 'application/json'
        ])->get("https://api.flutterwave.com/v3/transactions/{$trxid}/verify");
    } else {
        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_KEY'),
            'Content-Type' => 'application/json'
        ])->get("https://api.paystack.co/transaction/verify/$txref");
    }

    // Handle response
    if ($response->successful()) {
        $data = $response->json();

        if ($data['status'] == 'success') {
            $amount = $data['data']['amount'] / 100;
            $userEmail = $data['data']['customer']['email'];
            $licenseId = $data['data']['meta']['license_id'] ?? $data['data']['metadata']['license_id'];
            $raveRef = $data['data']['flw_ref'] ?? $data['data']['reference'];
            $raveExist = Payment::where('trx_ref', $raveRef)->first();

            if ($raveExist) {
                return Redirect::to('/user');
            }

            $user = User::where('email', $userEmail)->first();
            $license = LicenseType::where('amount', $amount)->first();
            $licenseUser = License::where('user_id', $user->id)->where('id', $licenseId)->first();

            if ($license->duration == 'monthly') {
                $value = Carbon::now()->addMonth();
            } elseif ($license->duration == 'quarter') {
                $value = Carbon::now()->addMonths(3);
            } elseif ($license->duration == 'bi-yearly') {
                $value = Carbon::now()->addMonths(6);
            } elseif ($license->duration == 'yearly') {
                $value = Carbon::now()->addYear();
            }

            if ($licenseUser) {
                $licenseUser->update([
                    'active' => 1,
                    'expiry_date' => $value
                ]);

                Payment::create([
                    'user_id' => $user->id,
                    'trx_ref' => $raveRef,
                    'gateway' => strtoupper($checkout),
                    'amount' => $amount,
                    'licensetype_id' => $license->id,
                    'info' => 'Payment Successful for ' . $license->name . ' Activation/Renewal'
                ]);
                echo "Transaction Successfully kindly recheck the dashboard";
                return Redirect::to('/user');
            }
        } else {
            session()->flash('failed', 'Whoops, Your payment was not successful');
            return Redirect::to('/user');
        }
    } else {
        session()->flash('failed', 'Whoops, Your payment was not successful');
        return Redirect::to('/user');
    }
}
}
