<?php

namespace App\Models;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;


    protected $fillable=['conversation_id','sender_id','content','attachment','receiver_id','replay_on'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'receiver_id');
    }

    public function scopeLastMessage(Builder $query)
    {
        return $query->latest('created_at')->first();
    }
}
