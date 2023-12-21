<?php

namespace App\Models;

use App\Models\Otp;
use Illuminate\Foundation\Auth\User;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantUser extends User implements ShouldQueue, JWTSubject
{
    use HasFactory, Notifiable;

    protected $guard = ["restaurant"];

    protected $fillable = [
        'name',
        'user_name',
        'email',
        'photo',
        'phone',
        'password',
        'status',
        'restaurant_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey(); // Assuming your admin model has a primary key called "id".
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


    public function otp()
    {
        return $this->hasOne(Otp::class, 'restaurant_user_id','id');
    }





    protected static function booted()
    {
        // Event for decrypting the content after a Post is retrieved
        static::retrieved(function ($user) {
            $user->photo = retriveMedia() . $user->photo;
        });
    }
}
