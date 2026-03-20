<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Interest rate management
            $table->enum('interest_type', ['fixed', 'reducing_balance', 'tiered'])->default('fixed');
            $table->decimal('penalty_rate', 5, 4)->default(0.0500); // 5% penalty rate
            $table->decimal('penalty_amount', 15, 2)->default(0);
            
            // Payment modes
            $table->enum('payment_mode', ['cash', 'mobile_money', 'bank_transfer', 'check'])->default('bank_transfer');
            $table->string('mobile_money_provider')->nullable(); // M-Pesa, Airtel Money, etc.
            $table->string('mobile_money_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            
            // Repayment features
            $table->boolean('allows_partial_payment')->default(true);
            $table->boolean('allows_early_payment')->default(true);
            $table->decimal('early_payment_fee', 15, 2)->default(0);
            
            // Overdue tracking
            $table->integer('days_overdue')->default(0);
            $table->decimal('penalty_accrued', 15, 2)->default(0);
            $table->date('last_payment_date')->nullable();
            $table->date('next_payment_date')->nullable();
            
            // Communication
            $table->boolean('sms_reminders_enabled')->default(true);
            $table->boolean('email_reminders_enabled')->default(true);
            $table->integer('reminder_days_before')->default(3);
            
            // Risk assessment
            $table->integer('credit_score')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high'])->nullable();
            $table->decimal('dti_ratio', 5, 4)->nullable(); // Debt-to-income ratio
            $table->boolean('ai_approved')->default(false);
            $table->text('risk_factors')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'interest_type',
                'penalty_rate', 
                'penalty_amount',
                'payment_mode',
                'mobile_money_provider',
                'mobile_money_number',
                'bank_account_number',
                'bank_name',
                'allows_partial_payment',
                'allows_early_payment',
                'early_payment_fee',
                'days_overdue',
                'penalty_accrued',
                'last_payment_date',
                'next_payment_date',
                'sms_reminders_enabled',
                'email_reminders_enabled',
                'reminder_days_before',
                'credit_score',
                'risk_level',
                'dti_ratio',
                'ai_approved',
                'risk_factors'
            ]);
        });
    }
};
