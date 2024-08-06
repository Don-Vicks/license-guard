<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checkout;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    $user = Auth::user();

    if ($user) {
        if (Gate::allows('access-admin-panel')) {
            return redirect('/admin');
        } else {
            return redirect('/user');
        }
    } else {
        return redirect('/login'); // or wherever you want unauthenticated users to go
    }
});


Route::get('checkout/{id}', [Checkout::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('checkout/verify/confirm', [Checkout::class, 'verify'])->name('checkout.verify')->withoutMiddleware('web');
