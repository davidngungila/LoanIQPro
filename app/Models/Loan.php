<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'branch_id',
        'loan_officer_id',
        'loan_type',
        'amount',
        'interest_rate',
        'term_months',
        'purpose',
        'collateral_description',
        'monthly_payment',
        'total_payment',
        'outstanding_balance',
        'status',
        'application_date',
        'approval_date',
        'disbursement_date',
        'first_payment_date',
        'maturity_date',
        'approved_by',
        'disbursed_by',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:4',
        'term_months' => 'integer',
        'monthly_payment' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'application_date' => 'date',
        'approval_date' => 'date',
        'disbursement_date' => 'date',
        'first_payment_date' => 'date',
        'maturity_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function loanOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disbursedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDefaulted($query)
    {
        return $query->where('status', 'defaulted');
    }

    public function getRemainingTermAttribute(): int
    {
        if (!$this->first_payment_date) {
            return $this->term_months;
        }
        
        $monthsPaid = now()->diffInMonths($this->first_payment_date);
        return max(0, $this->term_months - $monthsPaid);
    }

    public function getDaysPastDueAttribute(): int
    {
        if ($this->status !== 'active' || !$this->first_payment_date) {
            return 0;
        }
        
        $nextPaymentDate = $this->first_payment_date->copy()->addMonths($this->term_months - $this->remaining_term);
        
        if (now()->greaterThan($nextPaymentDate)) {
            return now()->diffInDays($nextPaymentDate);
        }
        
        return 0;
    }

    public function getTotalRepaidAttribute(): float
    {
        return $this->repayments()->sum('amount');
    }

    public function getRemainingBalanceAttribute(): float
    {
        return $this->outstanding_balance - $this->total_repaid;
    }

    public function isOverdue(): bool
    {
        return $this->days_past_due > 0;
    }
}
