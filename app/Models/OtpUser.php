<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'otp', 'expires_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return now()->gt($this->expires_at);
    }

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeInvalid($query)
    {
        return $query->where('expires_at', '<=', now());
    }
}
