<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chair;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UserAttendance extends Model
{
    use HasFactory;
    protected $fillable =[
        'restaurant_id',
        'public_place_id',
        'chair_id',
        'user_id',
        'created_at',
        'updated_at'
    ];


    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function chairs(): BelongsTo
    {
        return $this->belongsTo(Chair::class,'chair_id','id');
    }


    public function publicPlace(): BelongsTo
    {
        return $this->belongsTo(PublicPlace::class,'public_place_id','id');
    }



}
