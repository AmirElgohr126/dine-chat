<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ConversationDeleteAfter extends Model
{
    use HasFactory;

    protected $table = 'conversation_delete_after';

    protected $fillable = [
        'period_reservation_deleted_after',
        'period_reservation_unit',
        // ==================================
        'period_reservation_deleted_after_followers',
        'period_reservation_unit_followers'
    ];


    public static function firstRowNormalCase(): string
    {
        $model = self::findOrNew(1);
        // if new row created implement this
        if (!$model->exists) {
            $model->period_reservation_deleted_after = 1;
            $model->period_reservation_unit = 'hour';
            $model->period_reservation_deleted_after_followers = 1;
            $model->period_reservation_unit_followers = 'hour';
            $model->save();
        }
        $period = self::determinePeriodInNormalCase($model);
        return $period->format('Y-m-d H:i:s');
    }


    public static function determinePeriodInNormalCase($model):Carbon
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


    // =================================================================================

    public static function firstRowForFollower(): string
    {
        $model = self::findOrNew(1);
        // if new row created implement this
        if (!$model->exists) {
            $model->period_reservation_deleted_after_followers = 1;
            $model->period_reservation_unit_followers = 'hour';
            $model->period_reservation_deleted_after = 1;
            $model->period_reservation_unit = 'hour';
            $model->save();
        }
        $period = self::determinePeriodForFollower($model);
        return $period->format('Y-m-d H:i:s');
    }



    public static function determinePeriodForFollower($model):Carbon
    {
        $value = $model->period_reservation_deleted_after_followers;
        switch ($model->period_reservation_unit_followers) {
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


    // =================================================================================

}
