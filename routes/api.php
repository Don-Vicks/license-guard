<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LicenseController;
use App\Http\Controllers\CronController;

Route::get('validate/license', [LicenseController::class, 'validate'])->name('validate.license');
Route::get('run/cronjob', [CronController::class, 'job']);