<?php

namespace App\Models;

use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodRating extends Model
{
    use HasFactory;


    protected $fillable =[
        'rating',
        'restaurant_id',
        'food_id',
        'user_id',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class,'food_id','id');
    }


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
