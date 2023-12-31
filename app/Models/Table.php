<?php

namespace App\Models;

use App\Models\Chair;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable =[
        'restaurant_id',
        'x',
        'y',
        'key',
        'img',
        'name'
    ];


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }

}
