<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanedChats extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','conversation_id','status','reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class,'conversation_id','id');
    }

    public function scopeBan($query)
    {
        return $query->where('status','ban');
    }

    public function scopeReport($query)
    {
        return $query->where('status','report');
    }

    public function scopeUser($query,$userId)
    {
        return $query->where('user_id',$userId);
    }

    public function scopeConversation($query,$conversationId)
    {
        return $query->where('conversation_id',$conversationId);
    }
}
