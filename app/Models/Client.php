<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\facades\DB;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'email',
        'phone_number',
        'subscription_end_date',
        'status',
        'revenue',
        'address',
    ];

    protected $casts = [
        'subscription_end_date' => 'datetime',
        'revenue' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isActive()
    {
        return $this->status === 'aktif' && $this->subscription_end_date->isFuture();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->subscription_end_date->isfuture() &&
        $this->subscription_end_date->diffInDays(now()) <= $days;
    }
}
