<?php

namespace App\Models;

use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chair extends Model
{
    use HasFactory;
    protected $fillable = [
        'chair_number',
        'table_id',
        'restaurant_id',
    ];


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
    public function table()
    {
        return $this->belongsTo(Table::class,'restaurant_id','id');
    }

    
}
