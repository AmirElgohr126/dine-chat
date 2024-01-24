<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::routes(['middleware' => ['auth:api']]);

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    // Check if the user is authorized to access the conversation
    return $user->canAccessConversation($conversationId);
});
Broadcast::channel('restaurant.{restaurantId}', function ($user, $restaurantId) {
    // Check if the user is authorized to access the conversation
    return $user->canAccessRestaurant($restaurantId);
});
Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    // Check if the user is authorized to access the room
    return $user->canAccessRoom($roomId);
});
Broadcast::channel('game.{gameId}', function ($user, $gameId) {
    // Check if the user is authorized to access the room
    return $user->canAccessGame($gameId);
});
