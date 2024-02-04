<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class SuperAdmin extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
        protected $fillable = [
        'name',
        'user_name',
        'email',
        'photo',
        'phone',
        'password',

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    protected static function booted()
    {
        // Event for decrypting the content after a Post is retrieved
        static::created(function ($user) {
            $user->notify(new CustomVerifyEmail);
        });
    }


    public function otp()
    {
        return $this->hasOne(OtpSuperAdmin::class);
    }
}
