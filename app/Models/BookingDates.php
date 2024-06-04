<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BookingDates extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_reservation_unit',
        'period_reservation_deleted_after',
        'period_logout_public_places',
        'period_logout_unit_public_places'
    ];


    public static function getDateOfPlace($type): Carbon|string
    {
        if ($type == 'restaurant') {
            return self::firstRowRestaurant();
        } else {
            return self::firstRowForPublicPlaces();
        }
    }


    /**
     * @return Carbon
     */
    public static function firstRowRestaurant(): Carbon
    {
        $model = self::findOrNew(1);
        if (!$model->exists) {
            $model->period_reservation_deleted_after = 1;
            $model->period_reservation_unit = 'hour';
            $model->save();
        }
        return self::determinePeriodForRestaurant($model);
    }


    public static function firstRowForPublicPlaces() : string
    {
        $model = self::findOrNew(1);
        if (!$model->exists) {
            $model->period_logout_public_places = 1;
            $model->period_logout_unit_public_places = 'hour';
            $model->save();
        }
        return self::determinePeriodForPublicPlaces($model)->format('Y-m-d H:i:s');

    }




    /**
     *
     * @param $model
     * @return Carbon
     */
    public static function determinePeriodForRestaurant($model): Carbon
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




    /**
     * return period for public places logout
     * @param $model
     * @return Carbon
     */
    public static function determinePeriodForPublicPlaces($model): Carbon
    {
        $value = $model->period_logout_public_places;
        switch ($model->period_logout_unit_public_places) {
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
