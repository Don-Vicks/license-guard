<?php

namespace App\Filament\User\Resources\PaymentResource\Pages;

use App\Filament\User\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
