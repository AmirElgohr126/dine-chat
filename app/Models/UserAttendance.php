<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Chair;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserAttendance extends Model
{
    use HasFactory;
    protected $fillable =[
        'restaurant_id',
        'chair_id',
        'table_id',
        'user_id',
    ];


    public function restaurant() {
        return $this->belongsTo(Restaurant::class,'restaurant_id','id');
    }
    public function users() {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function tables() {
        return $this->belongsTo(Table::class,'table_id','id');
    }
    public function chairs() {
        return $this->belongsTo(Chair::class,'chair_id','id');
    }




    protected static function booted()
    {
        static::addGlobalScope('createdWithinLastHour', function (Builder  $builder)
        {
            $builder->where('created_at', '>=', now()->subHours(1)); // last created from hour
        });
    }




}
