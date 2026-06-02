<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Informasi paket langganan
            $table->string('package_name');
            $table->decimal('price', 15, 2);
            $table->integer('duration_months');

            // Periode langganan
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            // Status langganan
            $table->enum('status', [
                'active',
                'inactive',
                'expired',
                'canceled',
            ])->default('inactive');

            // Waktu dan penghapusan data
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('end_date');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};