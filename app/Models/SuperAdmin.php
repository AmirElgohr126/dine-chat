<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_name',
        'email',
        'photo',
        'phone',
        'password',
        // Add other fields as necessary
    ];
}
