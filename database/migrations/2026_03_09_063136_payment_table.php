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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')
            ->constrained('clients')
            ->onDelete('cascade');

            $table->foreignId('subscription_id')
            ->nullable()
            ->constrained('subscriptions')
            ->onDelete('set null');

            $table->string('invoice_number')->unique();
            $table->decimal('amount', 15,2);

            $table->date('payment_date')->nullable();
            $table->date('due_date')->nullable();

            $table->enum('status', [
                'paid',
                'pending',
                'failed',
                'canceled',
            ])->default('pending');

            $table->enum('payment_method', [
                'credit_card',
                'transfer',
                'e-wallet',
            ])->default('transfer');

            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('proof_of_payment')->nullable();

            $table->foreignId('approved_by')
            ->nullable()
            ->constrained('users')
            ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('subscription_id');
            $table->index('status');
            $table->index('payment_date');
            $table->index('invoice_number');           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropUfExists('Payments');
    }
};
