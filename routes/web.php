<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checkout;
use Illuminate\Mail\Message;

Route::get('/', function () {
    return redirect('/user');
});

Route::get('checkout/{id}', [Checkout::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('checkout/verify/confirm', [Checkout::class, 'verify'])->name('checkout.verify')->withoutMiddleware('web');
