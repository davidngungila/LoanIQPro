<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'national_id',
        'passport_number',
        'employment_status',
        'employer_name',
        'monthly_income',
        'credit_score',
        'kyc_status',
        'kyc_verified_at',
        'kyc_verified_by',
        'is_active',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'kyc_verified_at' => 'datetime',
        'monthly_income' => 'decimal:2',
        'credit_score' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->loans()->where('status', 'active');
    }

    public function completedLoans(): HasMany
    {
        return $this->loans()->where('status', 'completed');
    }

    public function kycVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kyc_verified_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeKycVerified($query)
    {
        return $query->where('kyc_status', 'verified');
    }

    public function scopeKycPending($query)
    {
        return $query->where('kyc_status', 'pending');
    }

    public function scopeKycRejected($query)
    {
        return $query->where('kyc_status', 'rejected');
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    public function getTotalLoanAmountAttribute(): float
    {
        return $this->loans()->sum('amount');
    }

    public function getOutstandingLoanAmountAttribute(): float
    {
        return $this->activeLoans()->sum('outstanding_balance');
    }

    public function getLoanCountAttribute(): int
    {
        return $this->loans()->count();
    }

    public function getActiveLoanCountAttribute(): int
    {
        return $this->activeLoans()->count();
    }
}
