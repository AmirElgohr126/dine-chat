<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralNotification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'content', 'attachment'];



    public static function requestChat($user,$sender_name,$message)
    {
        return self::create(
            [
                'user_id' => $user->id,
                'title' => "$sender_name" .'send you new request Chat',
                'content' => $message->content,
                'attachment' => $message->attachment ?? null
            ]);
    }
    public static function acceptChat($user,$reviver_name)
    {
        return self::create(
            [
                'user_id' => $user->id,
                'title' => "$reviver_name" .'accept request Chat',
            ]);
    }
    public static function sendNewMessage($user,$sender_name,$message)
    {
        return self::create(
            [
                'user_id' => $user->id,
                'title' => "$sender_name" .'send new message',
                'content' => $message->content,
                'attachment' => $message->attachment ?? null
            ]);
    }

}
