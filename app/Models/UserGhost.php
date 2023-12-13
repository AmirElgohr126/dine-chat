<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGhost extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'photo',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
