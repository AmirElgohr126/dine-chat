<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chair;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserAttendance extends Model
{
    use HasFactory;
    protected $fillable =[
        'restaurant_id',
        'chair_id',
        'user_id',
        'created_at',
        'updated_at'
    ];


    public function restaurant() {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
    public function users() {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function chairs() {
        return $this->belongsTo(Chair::class,'chair_id','id');
    }





}
