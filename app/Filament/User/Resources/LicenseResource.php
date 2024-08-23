<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\LicenseResource\Pages;
use App\Filament\User\Resources\LicenseResource\RelationManagers;
use App\Models\License;
use App\Models\LicenseType;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationLabel = 'Your Licenses';
    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type_id')
                    ->label('License Type')
                    ->options(fn () => LicenseType::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive(),

                TextInput::make('key')
                    ->label('License Key')
                    ->default('License_' . Str::random(16))
                    ->required()
                    ->readOnly(),

                TextInput::make('link')
                    ->required()
                    ->url()
                    ->placeholder('https://teendev.dev'),

                Hidden::make('user_id')
                    ->default(fn () => Auth::user()->id),

                Hidden::make('expiry_date')
                    ->default(fn ($record) => $record ? $record->expiry_date : now()),

                Hidden::make('active')
                    ->default(fn ($record) => $record ? $record->active : 0),

                Toggle::make('active')->label('License Status, To activate you can make use of the Make Payment button')->disabled()
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type.name')->label('License Name'),
                TextColumn::make('type.duration')->label('Billing Cycle'),
                TextColumn::make('type.amount')->label('Billing Amount'),
                TextColumn::make('link')->searchable(),
                TextColumn::make('key')->searchable(),
                TextColumn::make('expiry_date'),
                TextColumn::make('last_accessed_at')->label('You Lastly Accessed At'),
                TextColumn::make('number_of_accesses')->label('Number Of Acesses '),
                BooleanColumn::make('active')
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('payment')->label('Make Payment')
                ->visible(fn (License $record) => $record->active != 1)
    ->url(fn (License $record): string => route('checkout', $record->id))
    ->openUrlInNewTab(),
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
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }
}
