<?php

namespace App\Filament\User\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPayments extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
        ->description('Here are the last 10 payments you have made')
            ->query(
                Payment::query()->where('user_id', auth()->user()->id)->take(10),
            )
            ->columns([
                TextColumn::make('trx_ref'),
                TextColumn::make('amount'),
                TextColumn::make('gateway'),
                TextColumn::make('created_at')->dateTime()
            ]);
    }
}
