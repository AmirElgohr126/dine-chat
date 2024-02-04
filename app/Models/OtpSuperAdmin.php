<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpSuperAdmin extends Model
{
    use HasFactory;

    protected $table = 'otp_super_admins';

    protected $fillable = [
        'super_admin_id',
        'otp',
        'expires_at',
    ];

    public function superAdmin()
    {
        return $this->belongsTo(SuperAdmin::class);
    }

    public function isExpired()
    {
        return $this->expires_at instanceof \Carbon\Carbon ? $this->expires_at->isPast() : true;
    }
}
