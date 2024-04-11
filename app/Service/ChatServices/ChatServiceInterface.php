<?php
namespace App\Service\ChatServices;

interface ChatServiceInterface
{
    // check chat with your self
    public function chatWithYourSelf($authId,$IdChatWith); // finished
    // check corresponding user is in reservation in this restaurant or no
    public function checkAnotherPersonInPlace($PlaceId, $IdChatWith, $place); // finished
    //  Handle the case where the conversation already exists
    public function checkChatExist($user,$request , $place , $placeId); // finished
    //  Handle the case if follow or no
    public function checkFollow($user, $request);

    // create chat
    public function createChat($dataDeleted, $place, $user, $request,$placeId);

}
