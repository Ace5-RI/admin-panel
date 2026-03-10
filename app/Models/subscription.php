<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subscription extends Model
{
    use SoftDeletes;

    protected $table = "Subcriptions";

    protected $fillable = [
        'client_id',
        'package_name',
        'price',
        'duration_months',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'duration_months' => '12',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
          'active' => '<span class="badge" style="background-color: #339900; color: white;" >Active</span>',
            'inactive' => '<span class="badge" style="background-color:#6c757d; color:white;">Inactive</span>',
            'expired' => '<span class="badge" style="background-color:#dc3545; color:white;">Expired</span>',
            'canceled' => '<span class="badge" style="background-color:#ffc107; color:black;">Canceled</span>',
            default => '<span class="badge" style="background-color:#6c757d; color:white;">Unknown</span>'  
        };
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp' . number_format($this->price, 0, ',','.');
    }

    public function getDaysLeftAttribute()
    {
        if($this->status !== 'active') return 0;
        return now()->diffInDays($this->end_date, false);
    }

    public function getExpiredAttribute()
    {
        return $this->end_date < now();
    }

    public function getExpiredSoonAttribute()
    {
        if ($this->status !== 'active') return false;
    }


}
