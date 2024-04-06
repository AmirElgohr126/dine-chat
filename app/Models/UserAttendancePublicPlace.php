<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAttendancePublicPlace extends Model
{
    use HasFactory;

    protected $table = 'user_attendance_public_places';

    protected $fillable = [
        'user_id',
        'public_place_id',
        'created_at',
        'updated_at'
    ];

    public function publicPlace(): BelongsTo
    {
        return $this->belongsTo(PublicPlace::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
