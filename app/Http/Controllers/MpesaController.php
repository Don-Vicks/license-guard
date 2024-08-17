<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransaction;
use App\Services\MpesaService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    /**
     * @param StkPushRequest $request
     * @return string[]
     */
    public function stkPush(StkPushRequest $request)
    {
        $mpesaService = new MpesaService();
        $data = [
            'amount' => $request->amount,
            'phoneNumber' => $request->phoneNumber,
            'transactionType' => 'CustomerPayBillOnline',
            'accountReference' => 'accountReference',

        ];
        $response = $mpesaService->sendSTKPush($data);
        $transaction = MpesaTransaction::query()
            ->create([
                'MerchantRequestID' => $response['response']['MerchantRequestID'],
                'CheckoutRequestID' => $response['response']['CheckoutRequestID'],
                'phone_number' => $request->phoneNumber,
                'transaction_amount' => $request->amount,
            ]);

        if ($transaction) {
            return [
                'success' => true,
                'transactionDateTime' => $transaction->created_at,
                'transaction' => $transaction
            ];
        }
        return [
            'success' => 'error',
            'message' => 'Failed to initiate Transaction'
        ];
    }

    public function syncPayment()
    {
        return [
            'success' => true,
        ];
    }

    public function confirmation(Request $request){
        $transaction = MpesaTransaction::query()
            ->where('phone_number',$request->phoneNumber)
            ->where('transaction_amount',$request->amount)
            ->whereBetween('transaction_date',[Carbon::parse($request->dateTime),Carbon::parse($request->dateTime)->endOfMonth()])
            ->first();
        if($transaction){
            //return the transaction
            return [
                'success' => true,
                'message' => _('Transaction Confirmed'),
                'amount' => $transaction->transaction_amount,
                'transaction' => $transaction
            ];
        }
        return [
            'success' => false,
            'message' => _('Transaction Not Found'),
        ];
    }
}
