<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required(),
                Select::make('permissions')
                    ->label('Permissions')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->options(Permission::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Role Name')->sortable()->searchable(),
                TextColumn::make('permissions.name')
                    ->label('Permissions')
                    ->sortable()
                    ->searchable()
                    ->limit(3),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($record) {
                        $currentUser = Auth::user();
                        if ($currentUser->hasRole($record->name)) {
                            Notification::make()
                                ->title('Action Not Allowed')
                                ->body('You cannot edit your own role.')
                                ->danger()
                                ->send();
                            return false;
                        }
                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        $currentUser = Auth::user();
                        foreach ($records as $record) {
                            if ($currentUser->hasRole($record->name)) {
                                Notification::make()
                                    ->title('Action Not Allowed')
                                    ->body('You cannot delete your own role.')
                                    ->danger()
                                    ->send();
                                return false;
                            }
                        }
                        return true;
                    }),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
