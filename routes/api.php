<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LicenseController;

Route::post('validate/license', [LicenseController::class, 'validate'])->name('validate.license');