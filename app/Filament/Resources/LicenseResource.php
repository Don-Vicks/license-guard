<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LicenseResource\Pages;
use App\Filament\Resources\LicenseResource\RelationManagers;
use App\Libraries\Core;
use App\Models\License;
use App\Models\LicenseType;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected $core;

    public function __construct(Core $core)
    {
        $this->core = $core;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->label('User')
                ->options(function (callable $get) {
                    return User::all()->pluck('name', 'id');
                })
                ->searchable()
                ->reactive(),
                Select::make('type_id')
                ->label('License Type')
                ->options(function (callable $get) {
                    return LicenseType::all()->pluck('name', 'id');
                })
                ->searchable()
                ->reactive(),
                TextInput::make('key')->label('License Key')->default('License_' . Str::random(16))->required(),
                TextInput::make('link')->required()->url()->prefix('https://'),
                TextInput::make('expiry_date')
                    ->default(function ($state){
                        $licenseType= $state['type_id']  ?? 'Basic Plan';
                        return app(Core::class)->licenceDuration($licenseType);

                    })
                    ->readOnly()
                    ->extraInputAttributes(['readonly'=>true])
            ->required(),

                //DatePicker::make('expiry_date')->required(),
                Toggle::make('active')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('type.name')->label('License Type')->sortable()->searchable(),
                TextColumn::make('type.amount')->label('License Price'),
                TextColumn::make('key'),
                TextColumn::make('link'),
                TextColumn::make('expiry_date'),
                TextColumn::make('last_accessed_at'),
                TextColumn::make('number_of_accesses'),
                ToggleColumn::make('active'),

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
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }
}
