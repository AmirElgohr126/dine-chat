<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDates extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_reservation_unit',
        'period_reservation_deleted_after'
    ];

    public static function firstRow()
    {
        $model = self::find(1);
        $period = self::determainPeriod($model);
        return $period;
    }

    public static function determainPeriod($model)
    {
        $value = $model->period_reservation_deleted_after;
        switch ($model->period_reservation_unit) {
            case 'year':
                return now()->addYears($value);
            case 'month':
                return now()->addMonths($value);
            case 'week':
                return now()->addWeeks($value);
            case 'day':
                return now()->addDays($value);
            case 'hour':
                return now()->addHours($value);
            default:
                return now()->addHour();
        }
    }
}
