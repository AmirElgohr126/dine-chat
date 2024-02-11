<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantPackage extends Model
{
        use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'restaurant_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'photo',
        'description',
        'price_per_month',
        'price_per_year',
        'status',
        'period_finished_after',
        'features',
        'limitations',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'features' => 'array',
        'limitations' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];



/**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            // If price_per_year is not provided and price_per_month is provided, calculate price_per_year
            if (empty($package->price_per_year) && !empty($package->price_per_month)) {
                $package->price_per_year = $package->price_per_month * 12;
            }
        });
    }
}
