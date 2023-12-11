<?php

namespace App\Models;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'rating'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
