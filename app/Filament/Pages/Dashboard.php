<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\NewPayments;
use App\Filament\Widgets\NewUsers;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            NewUsers::class,
            NewPayments::class,
        ];
    }
}
