<?php

namespace App\Filament\User\Resources\LicenseResource\Pages;

use App\Filament\User\Resources\LicenseResource;
use App\Models\License;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

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
            Actions\DeleteAction::make(),
        ];
    }
}
