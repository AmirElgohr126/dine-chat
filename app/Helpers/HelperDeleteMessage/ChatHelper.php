<?php
use App\Models\Restaurant;

function determainPeriod(Restaurant $model)
{
    $value = $model->period_deleted_after;
    switch ($model->period_unit) {
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



?>
