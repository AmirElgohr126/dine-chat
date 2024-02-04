<?php
namespace App\Service\ChatServices;

interface ChatServiceInterface
{
    // check chat with your self
    public function chatWithYourSelf($authId,$IdChatWith);
    // check corresponding user is in reservation in this restaurant or no
    public function checkAnotherPersonInRestaurant($restaurantId, $IdChatWith);
    // 1- Handle the case where the conversation already exists
    public function checkChatExist($user,$request,$restaurant);
    // 1- Handle the case if follow or no
    public function checkFollow($user, $request, $setting);

}
