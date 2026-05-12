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
        Schema::create('clients',function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('company');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->date('subscription_start_date');
            $table->date('subscription_end_date');
            $table->enum('status',['aktif','nonaktif','expired'])->default('aktif');
            $table->decimal('revenue',15,2)->default(0);
            $table->text('Langganan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
