<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadWord extends Model
{
    use HasFactory;

    protected $table = 'bad_words';

    protected $fillable = [
        'word',
        'is_active',
    ];



    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('word', 'like', '%' . $search . '%');
    }

    // public function scopeFilter($query, $filters)
    // {
    //     if (isset($filters['search']) && !empty($filters['search'])) {
    //         $query->search($filters['search']);
    //     }

    //     if (isset($filters['status']) && !empty($filters['status'])) {
    //         if ($filters['status'] == 'active') {
    //             $query->active();
    //         } else {
    //             $query->inactive();
    //         }
    //     }

    //     return $query;
    // }
}
