<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'Payments';
    protected $fillable = [
        'subcription_id',
        'payment_id',
        'invoice_number',
        'amount',
        'payment_date',
        'due_date',
        'status',
        'payment_method',
        'bank_name',
        'account_name',
        'account_number',
        'proof_of_payment',
        'approved_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_method' => 'transfer',
    ];

    public function subcription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getclientAttribute()
    {
        return $this->subcription?->client;
    }

    public function getFormattedAmoutAttribute()
    {
        return 'Rp' . number_format((float)$this->amount, 0, ',','.');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'paid' => '<span class="badge" style="background: #10B981; color: white;">Paid</span>',
            'pending' => '<span class="badge" style="background: #F59E0B; color: white;">Pending</span>',
            'failed' => '<span class="badge" style="background: #EF4444; color: white;">Failed</span>',
            'canceled' => '<span class="badge" style="background: #6B7280; color: white;">Canceled</span>',
            'default' => '<span class="badge" style="background: #6B7280; color: white;">Unknown</span>'
        };
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePendinng($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status','failed');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status','canceled');
    }

    public function scopeDateBetween($query, $start, $end)
    {
        return $query->whereBetween('payment_date', [$start, $end]);
    }

    public function UpdateClientRevenue()
    {
        if ($this->subsctiption && $this->subctiption->client) {
            $totalRevenue = Payment::whereHas('subcription',function($query){
                $query->where('client_id', $this->subcription->client_id);
            })->where('status','paid')->sum('amount');

            $this->subctiption->client->update(['revenue' => $totalRevenue]);
        } 
    }

    public function approve($approvedBy = null)
    {
        $this->update([
            'status' => 'paid',
            'approved_by' => $approvedBy ?? auth()->id(),
            'payment_date' => $this->payment_date ?? now(),
        ]);

        $this->UpdateClientRevenue();

        if ($this->subcription) {
            $this->extendSubscription();
        }
        return $this;
    }

    public function cancel()
    {
        $this->update([
            'status' => 'canceled',
        ]);

        $this->updateClientRevenue();

        return $this;
    }
}
