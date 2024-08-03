<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NewUsers extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
            )
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email')->copyable(),
                TextColumn::make('phone_number')->copyable(),
                TextColumn::make('created_at')->label('Registered at')
            ]);
    }
}
