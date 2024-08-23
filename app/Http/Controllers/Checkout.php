<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Models\License;
use App\Models\LicenseType;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Checkout extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $license = License::find($request->id);
        if (!$license) {
            return Redirect::back()->with('error', 'License not found.');
        }

        $licenseType = LicenseType::find($license->type_id);
        if (!$licenseType) {
            return Redirect::back()->with('error', 'License type not found.');
        }

        $amount = $request->plan;

        $data = [
            'tx_ref' => Str::random(16), // Unique transaction reference
            'amount' => $licenseType->amount,
            'currency' => 'KSH. ',
            'redirect_url' => route('checkout.verify'),
            'meta' => [
                'license_id' => $request->id,
                'amount' => $amount,
            ],
            'customer' => [
                'email' => $user->email,
                'phonenumber' => $user->phone_number,
                'name' => $user->name,
            ],
            'customizations' => [
                'title' => 'License Payment',
                'logo' => 'https://teendev.dev/wp-content/uploads/2023/04/DARK-BLUE-e1682352569915.png'
            ]
        ];

        // Redirect to payment page with query parameters
        return redirect()->to($data['redirect_url'] . '?tx_ref=' . $data['tx_ref'] . '&amount=' . $data['amount'].'&transaction_id='. fake()->uuid().
        '&license_id='.$license->id.'&license_type_id='.$licenseType->id.'&payment_id='. fake()->uuid().'&user_id='.$user->id);
    }

    public function verify(Request $request)
    {
        $transactionId = $request->query('transaction_id'); // Access query parameter
        $licenseId = $request->query('license_id'); // Access query parameter
        $amount = $request->query('amount'); // Access query parameter
        $licenseTypeId = $request->query('license_type_id');

        if ($transactionId && $licenseId && $licenseTypeId) {
            $user = User::find($request->query('user_id'));
            $license = LicenseType::where('id', $licenseTypeId)->first();

            if (!$license) {
                session()->flash('failed', 'License type not found.');
                return Redirect::to('/user');
            }

            $licenseUser = License::where('user_id', $user->id)->where('id', $licenseId)->first();

            if ($licenseUser) {
                // Determine the expiry date based on the license duration
                switch ($license->duration) {
                    case 'monthly':
                        $value = Carbon::now()->addMonth();
                        break;
                    case 'quarterly':
                        $value = Carbon::now()->addMonths(3);
                        break;
                    case 'bi-yearly':
                        $value = Carbon::now()->addMonths(6);
                        break;
                    case 'yearly':
                    case 'annually':
                        $value = Carbon::now()->addYear();
                        break;
                    case 'daily':
                        $value = Carbon::now()->addDay();
                        break;
                    case 'weekly':
                        $value = Carbon::now()->addWeek();
                        break;
                    case 'enterprise':
                        $value = Carbon::now()->addYears(2);
                        break;
                    default:
                        $value = Carbon::now()->addDays(7);
                        break;
                }

                $licenseUser->update([
                    'active' => 1,
                    'expiry_date' => $value,
                ]);

                Payment::create([
                    'user_id' => $user->id,
                    'license_id' => $licenseUser->id,
                    'trx_ref' => Str::uuid(),
                    'gateway' => 'Flutterwave',
                    'amount' => $amount,
                    'info' => 'Payment Successful for ' . $license->name . ' Activation/Renewal'
                ]);

                $licenseUser->update([
                    'type_id' => $licenseTypeId,
                ]);

                // Optionally send email notification
                // Mail::to($user)->queue(new LicensePaid($licenseUser, $amount, $value, $license));

                return Redirect::to('/user');
            } else {
                session()->flash('failed', 'License not found for user.');
                return Redirect::to('/user');
            }
        } else {
            session()->flash('failed', 'Transaction ID or License ID not found.');
            return Redirect::to('/user');
        }
    }
}
