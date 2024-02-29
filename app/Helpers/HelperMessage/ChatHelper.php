<?php

use App\Models\Conversation;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Http;

function determainPeriod( $model)
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


function getOtherUser(Conversation $chat, $authId)
{
    $sender_id = $chat->sender_id;
    $receiver = $chat->receiver_id;
    switch ($authId) {
        case $sender_id:
            return $receiver;
        case $receiver:
            return $sender_id;
    }
}


function sendEvent($channel, $event, $data, $userToken)
{
    $url = 'http://localhost:3000/broadcast';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $userToken
    ])->post($url, [
                'channel' => $channel,
                'event' => $event,
                'data' => $data,
            ]);
    return $response->json();
}
