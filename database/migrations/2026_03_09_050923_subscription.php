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
        Schema::dropIfExists('Subcription');
        Schema::create('Subcription', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subcription_id')
            ->constrained('subcription')
            ->onDelete('cascade');

            // Informasi paket langganan
            $table->string('package_name');
            $table->decimal('price', 15,2);
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

            // Waktu dan penghapusan data langganan
            $table->timestamps();
            $table->softDeletes();

            $table->index('subcription_id');
            $table->index('status');
            $table->index('end_date');
            $table->index(['start_date', 'end_date']);
        }); 

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcription');
    }
};
