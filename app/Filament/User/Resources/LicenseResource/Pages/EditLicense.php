<?php

namespace App\Filament\User\Resources\LicenseResource\Pages;

use App\Filament\User\Resources\LicenseResource;
use App\Libraries\Core;
use App\Models\License;
use App\Models\LicenseType;
use App\Models\MpesaTransaction;
use App\Models\Payment;
use App\Services\MpesaService;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditLicense extends EditRecord
{
    protected static string $resource = LicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('payment')->label('Make Payment')
            ->visible(fn (License $record) => $record->active != 1)
->url(fn (License $record): string => route('checkout', $record->id))
->openUrlInNewTab(),
            \Filament\Tables\Actions\Action::make('payment')->label('Make Payment')
                ->visible(fn (License $record) => $record->active != 1)
                ->url(fn (License $record): string => route('checkout', $record->id))
                ->openUrlInNewTab(),
            Action::make('mpesa_payment')
                ->form([
                    TextInput::make('phone_number')->required(),
                    TextInput::make('amount')->required()->default(function (License $license)
                    {
                        return $license->type->amount;
                    }),
                ])
                ->action(function (array $data) {
                    (new MpesaService())->makeStkPayment($data);
                }),
            Action::make('confirm_payment')
                ->form([
                    TextInput::make('phone_number')->required(),
                    TextInput::make('amount')->required()->default(function (License $license)
                    {
                        return $license->type->amount;
                    })->readOnly(fn (License $record) => $record->type->amount != null),
                ])
                ->action(function (array $data, License $license) {
                    $transaction = MpesaTransaction::query()
                        ->where('phone_number', $data['phone_number'])
                        ->where('transaction_amount', $data['amount'])
                        ->whereBetween('transaction_date', [Carbon::parse(now())->startOfDay(), Carbon::parse(now())->endOfMonth()])
                        ->first();
                    if ($transaction) {
                        $user = auth()->user();
                        $licenseType = LicenseType::where('id', $license->type->id)->first();

                        if (!$licenseType) {
                            session()->flash('failed', 'License type not found.');
                        }

                        $licenseUser = License::where('user_id', $user->id)->where('id', $license->id)->first();

                        if ($licenseUser) {
                            // Determine the expiry date based on the license duration
                            $value = Core::licenceDuration($licenseType->duration);
                            $licenseUser->update([
                                'active' => 1,
                                'expiry_date' => $value,
                            ]);

                            Payment::create([
                                'user_id' => $user->id,
                                'license_id' => $licenseUser->id,
                                'trx_ref' => Str::uuid(),
                                'gateway' => 'Flutterwave',
                                'amount' => $data['amount'],
                                'info' => 'Payment Successful for ' . $licenseType->name . ' Activation/Renewal'
                            ]);

                            $licenseUser->update([
                                'type_id' => $licenseType->id,
                            ]);
                            //return the transaction
                            Notification::make()
                                ->success()
                                ->title(__('Transaction Confirmed'))
                                ->body(__('Your transaction has been confirmed.'))
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title(__('Transaction Confirmation Failed'))
                                ->body(__('Your transaction confirmation failed.'))
                                ->send();
                        }
                    }
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
