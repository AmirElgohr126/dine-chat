<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'privacy_policy',
        'about_us',
        'terms_conditions'
    ];
}
