<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'user_id',
        'followed_user',
        'contact_id',
        'follow_status'
    ];
}
