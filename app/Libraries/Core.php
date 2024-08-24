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
            case '1':
            case '4':
                $value = Carbon::now()->addMonth();
                break;
            case '2':
                $value = Carbon::now()->addMonths(3);
                break;
            case '3':
                $value = Carbon::now()->addMonths(6);
                break;
            case '5':
            case '8':
                $value = Carbon::now()->addYear();
                break;
            case '6':
                $value = Carbon::now()->addDay();
                break;
            case '7':
                $value = Carbon::now()->addWeek();
                break;
            case '9':
                $value = Carbon::now()->addYears(2);
                break;
            default:
                $value = Carbon::now()->addDays(7);
                break;
        }

        return $value;

    }
}
