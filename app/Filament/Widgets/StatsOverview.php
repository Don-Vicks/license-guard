<?php

namespace App\Filament\Widgets;

use App\Models\License;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Number of Users', User::query()->count())->description('All Users registered on this Application')->icon('heroicon-o-user'),
            Stat::make('Total License', License::query()->count())->description('Total number of License Active & Unactive')->icon('heroicon-o-key'),
            Stat::make('Active Licenses', License::where('expiry_date', '>', Carbon::now())->where('active', 1)->count())
            ->description('Licenses that are still valid') 
            ->icon('heroicon-o-check-circle'),
            Stat::make('Expired Licenses', License::where('expiry_date', '<', Carbon::now())->where('active', 0)->count())
            ->description('The expired licenses which haven\'t been renewed')
            ->icon('heroicon-o-question-mark-circle'),
            Stat::make('Total Payments', Payment::query()->count())
            ->description('Total number of Payments made')
            ->icon('heroicon-o-currency-dollar'),
            Stat::make('Total sum of Payments', Payment::query()->sum('amount'))
            ->description('Total sum of Payments made')
            ->icon('heroicon-o-arrow-down'),    
        ];
    }
}
