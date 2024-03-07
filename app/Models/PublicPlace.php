<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicPlace extends Model
{
    use HasFactory;

    protected $table = 'public_places';
    protected $fillable = [
        'name',
        'longitude',
        'latitude',
        'photo',
        'qr_link',
        'qr_path',
        'description',
        'status'
    ];
}
