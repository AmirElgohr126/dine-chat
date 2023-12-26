<?php

namespace App\Models;

use App\Models\FoodImage;
use App\Models\FoodRating;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Food extends Model implements TranslatableContract
{
    use HasFactory , Translatable;

    protected $table = 'foods';

    protected $fillable = ['restaurant_id','price'];
    public $translatedAttributes = ['name'];


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }

    public function rating()
    {
        return $this->hasMany(FoodRating::class,'food_id','id');
    }
    public function images()
    {
        return $this->hasOne(FoodImage::class,'food_id','id');
    }





    public function averageRating()
    {
        return $this->rating()->avg('rating');
    }


    public function userCount()
    {
        return $this->rating()->distinct('user_id')->count('user_id');
    }

    public static function findFoodById($foodId, $restaurantId)
    {
        return static::where('id', $foodId)->where('restaurant_id', $restaurantId)->first();
    }




}
