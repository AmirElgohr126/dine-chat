<?php

namespace App\Models;

use App\Models\FoodImage;
use App\Models\FoodRating;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Food extends Model implements TranslatableContract
{
    use HasFactory , Translatable;

    protected $table = 'foods';

    protected $fillable = ['restaurant_id','price','details','status'];
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


    public function foodRatings()
    {
        return $this->hasMany(FoodRating::class, 'food_id');
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




    public function userRating()
    {
        return $this->hasOne(FoodRating::class, 'food_id', 'id')
            ->where('user_id', Auth::id())
            ->latest();
    }

    /**
     * Get all foods for a specific restaurant with the authenticated user's rating.
     *
     * @param int $restaurantId The ID of the restaurant.
     * @return Collection
     */

    public static function getFoodsWithUserRatings($restaurantId)
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        return static::where('restaurant_id', $restaurantId)
            ->with([
            'images'=> function($query)
            {
                $query->select(['image', 'food_id']);
            },
            'userRating'=> function ($query){
                $query->select(['rating','food_id']);
            }])
            ->get();
    }

    public function toArray()
    {
        $array = parent::toArray();
        // Simplify the images field
        $array['images'] = $this->images ? $this->images->image : "";
        // Simplify the user rating field
        $array['user_rating'] = $this->userRating ? $this->userRating->rating : 0;
        // Include only the relevant translation for 'name' based on user's locale or a default
        $locale = app()->getLocale(); // get default locale
        $translation = $this->translations->where('locale', $locale)->first();
        $array['name'] = $translation ? $translation->name : $this->name;
        unset($array['translations']);

        return $array;
    }
}

