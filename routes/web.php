<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checkout;
use Illuminate\Mail\Message;

Route::get('/', function () {
    return view('welcome');
});

Route::get('checkout/{id}', [Checkout::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('checkout/verify/confirm', [Checkout::class, 'verify'])->name('checkout.verify')->withoutMiddleware('web');

// To Quickly send out a Mail
// Route::get('test', function () {
//       Mail::raw('Hello world', function (Message $message) {
//         $message->to('donvicks004@gmail.com')
//             ->from('example@example.com');
//     });
// });