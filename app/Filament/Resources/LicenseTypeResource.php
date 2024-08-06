<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LicenseTypeResource\Pages;
use App\Filament\Resources\LicenseTypeResource\RelationManagers;
use App\Models\LicenseType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LicenseTypeResource extends Resource
{
    protected static ?string $model = LicenseType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('amount')->required()->numeric(),
                Select::make('duration')->options([
                    'monthly' => 'Monthly',
                    'quarterly' => 'Three Months',
                    'bi-yearly' => '6 Months',
                    'yearly' => 'Yearly',
                    'daily' => 'Daily',
                    'weekly' => 'Weekly',
                    'free-trial' => 'Free Trial',
                    'enterprise' => 'Enterprise',
                ])->required()->reactive(),
                Toggle::make('status')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('amount'),
                TextColumn::make('duration'),
                BooleanColumn::make('status')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLicenseTypes::route('/'),
            'create' => Pages\CreateLicenseType::route('/create'),
            'edit' => Pages\EditLicenseType::route('/{record}/edit'),
        ];
    }
}
