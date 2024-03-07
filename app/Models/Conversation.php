<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id','sender_id','receiver_id','restaurant_id','status','deleted_at','has_profanity'
    ];




    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }

    /**
     * get messages conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class,'conversation_id','id')->latest();
    }

    /**
     * get the last messages
     */

    public function lastMessagesOfParticularConversation($conversationId)
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')
        ->where('conversation_id',$conversationId);
    }

    /**
     * if i have conversation what is the receiver
     */
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id','id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }


    public function scopeprofanity(Builder $query)
    {
        return $query->where('has_profanity',1);
    }
}
