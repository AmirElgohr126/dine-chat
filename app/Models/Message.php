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
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function scopeLastMessage(Builder $query)
    {
        return $query->latest('created_at')->first();
    }

    public function getCreatedAtAttribute($value)
    {
        // Format the created_at attribute as "year-month-day hour:min:second"
        return \Carbon\Carbon::parse($value)->format('Y-m-d h:i:s A');
    }

    public function getUpdatedAtAttribute($value)
    {
        // Format the deleted_at attribute as "year-month-day hour:min:second"
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d h:i:s A') : null;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }

    protected static function booted()
    {
        // Event for decrypting the content after a Post is retrieved
        static::retrieved(function ($message) {
            $message->attachment = retriveMedia().$message->attachment;
        });
    }
}
