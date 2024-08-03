<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Widgets\LatestPayments;
use App\Filament\User\Widgets\UserOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $title = 'Welcome';
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.user.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            UserOverview::class,
            LatestPayments::class,
        ];
    }
}
