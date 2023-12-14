<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'name',
        'photo',
        'phone',
        'status_on_app'
    ];



    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    protected static function booted()
    {
        // Event for decrypting the content after a Post is retrieved
        static::retrieved(function ($contact) {
            $contact->photo = retriveMedia().$contact->photo;
        });
    }

}
