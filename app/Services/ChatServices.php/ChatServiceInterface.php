<?php
namespace App\Services\ChatServices;

use Illuminate\Http\Request;
use App\Http\Requests\Contacts\ContactsRequest;
use App\Http\Requests\Contacts\FollowContactRequest;
use App\Http\Requests\Contacts\UnfollowContactRequest;

interface ChatServiceInterface
{
    // check chat with your self
    public function chatWithYourSelf($authId,$IdChatWith);
    // check corresponding user is in reservation in this restaurant or no
    public function checkAnotherPersonInRestaurant($restaurantId, $IdChatWith);
    // 1- Handle the case where the conversation already exists
    public function checkChatExist($user,$request,$restaurant);
    // 1- Handle the case if follow or no
    public function checkFollow($user, $request, $restaurant);

}
