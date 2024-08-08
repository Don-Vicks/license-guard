<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checkout;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    /*
     * Redirect to admin on / this will be guarded by a middleware when normal user logs in
     */
    return redirect('/admin');
});


Route::get('checkout/{id}', [Checkout::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('checkout/verify/confirm', [Checkout::class, 'verify'])->name('checkout.verify')->withoutMiddleware('web');
