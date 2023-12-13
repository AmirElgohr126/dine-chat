<?php

namespace App\Models;

use App\Models\Food;
use App\Models\Chair;
use App\Models\Table;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Restaurant extends Model implements TranslatableContract
{
    use HasFactory , Translatable;

    protected $fillable = [
        'number_of_floors',
        'number_of_departments',
        'latitude',
        'longitude',
        'phone',
        'images'
    ];

    public $translatedAttributes = ['name'];

    public function chairs()
    {
        return $this->hasMany(Chair::class,'restaurant_id','id');
    }
    public function tables()
    {
        return $this->hasMany(Table::class,'restaurant_id','id');
    }
    public function foods()
    {
        return $this->hasMany(Food::class,'restaurant_id','id');
    }
    public function ratings()
    {
        return $this->hasMany(Food::class,'restaurant_id','id');
    }



    public function userAttendance()
    {
        return $this->hasMany(UserAttendance::class,'restaurant_id','id');
    }
}
