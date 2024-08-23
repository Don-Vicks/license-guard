<?php

namespace App\Libraries;

use Illuminate\Support\Carbon;

class Core
{

    /**
     * Get duration from license plan
     * @param string $duration
     * @return Carbon
     */
    public static function licenceDuration(string $duration) : Carbon
    {
        switch ($duration){
            case 'monthly':
                $value = Carbon::now()->addMonth();
                break;
            case 'quarterly':
                $value = Carbon::now()->addMonths(3);
                break;
            case 'bi-annual':
                $value = Carbon::now()->addMonths(6);
                break;
            case 'yearly':
            case 'annually':
                $value = Carbon::now()->addYear();
                break;
            case 'daily':
                $value = Carbon::now()->addDay();
                break;
            case 'weekly':
                $value = Carbon::now()->addWeek();
                break;
            case 'enterprise':
                $value = Carbon::now()->addYears(2);
                break;
            default:
                $value = Carbon::now()->addDays(7);
                break;
        }
        return $value;
    }
}
