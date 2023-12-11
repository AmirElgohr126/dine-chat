<?php

namespace App\Models;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAttendance extends Model
{
    use HasFactory;



    public function restaurant() {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
}
