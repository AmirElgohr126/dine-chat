<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentsSubscriptionRestaurant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount',
        'payment_gateway',
        'transaction_id',
        'status',
        'paid_at',
        'payment_method',
        'currency',
        'billing_address',
        'card_last_four_digits',
        'customer_email',
        'description',
        'ip_address',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'metadata' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship with the User (assuming the user is a restaurant user).
     */
    public function user()
    {
        return $this->belongsTo(RestaurantUser::class, 'user_id');
    }

    /**
     * Relationship with the Subscription (linked to restaurant packages).
     */
    public function subscription()
    {
        return $this->belongsTo(RestaurantPackage::class, 'subscription_id');
    }
}
