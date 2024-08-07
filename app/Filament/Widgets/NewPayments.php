<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class NewPayments extends BaseTableWidget
{
    public function table(Table $table) : Table
    {
        return $table->query(
            Payment::query()->with(['user', 'license'])
        )->columns(
            Payment::filamentTableColumns()
        );

    }
}
