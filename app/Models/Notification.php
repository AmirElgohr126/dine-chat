<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;


    protected $fillable = [
        'restaurant_id',
        'title',
        'message',
        'last_sent_at',
        'status',
        'photo',
        'sent_at',
    ];

    // Define the relationship with the Restaurant model
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    // Accessor for sent_at attribute
    public function getSentAtAttribute($value)
    {
        return $this->formatDateTime($value);
    }

    // Accessor for last_sent_at attribute
    public function getLastSentAtAttribute($value)
    {
        return $this->formatDateTime($value);
    }

    // Accessor for created_at attribute
    public function getCreatedAtAttribute($value)
    {
        return $this->formatDateTime($value);
    }

    // Helper function to format date time
    protected function formatDateTime($value)
    {
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }



}
