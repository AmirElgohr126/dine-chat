<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'image',
    ];


    public function food()
    {
        return $this->belongsTo(Food::class,'food_id','id');
    }
}
