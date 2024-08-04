<?php

namespace App\Filament\User\Widgets;

use App\Models\License;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
class UserOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total License', License::where('user_id', auth()->user()->id)->count())
            ->description('The total number of License you have')
            ->icon('heroicon-o-key'),
            Stat::make('Active Licenses', License::where('user_id', auth()->user()->id)->where('expiry_date', '>', Carbon::now())->where('active', 1)->count())
            ->description('Licenses that are still valid') 
            ->icon('heroicon-o-check-circle'),
            Stat::make('Expired Licenses', License::where('user_id', auth()->user()->id)->where('expiry_date', '<', Carbon::now())->where('active', 0)->count())
            ->description('The expired licenses you haven\'t been renewed, this include Unpaid Licenses')
            ->icon('heroicon-o-question-mark-circle'),
            Stat::make('Total Payments', auth()->user()->payment()->count())
            ->description('Total number of Payments made')
            ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
