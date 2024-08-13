<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    use HasFactory;
    protected $table = 'mpesa_transactions';
    protected $fillable = [
        'MerchantRequestID',
        'CheckoutRequestID',
        'transaction_code',
        'phone_number',
        'transaction_date',
        'transaction_amount',
    ];
}


