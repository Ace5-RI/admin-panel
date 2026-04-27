<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type');        // login, create, edit, delete, invoice, payment
            $table->string('title');       // Judul aktivitas
            $table->text('detail')->nullable();  // Detail lengkap
            $table->string('user_name');   // Nama user yg melakukan
            $table->string('user_email')->nullable(); // Email user
            $table->string('status')->default('success'); // success, warning, error
            $table->string('ip_address')->nullable(); // IP address
            $table->timestamps();          // created_at, updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};