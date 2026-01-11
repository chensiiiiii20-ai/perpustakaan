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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Relasi user (boleh null jika guest)
            $table->unsignedBigInteger('user_id')->nullable();

            // Jenis aktivitas
            $table->string('action', 100)
                  ->comment('login, logout, create, update, delete');

            // Deskripsi aktivitas
            $table->text('description')->nullable();

            // Info request
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Default timestamp
            $table->timestamp('created_at')
                  ->useCurrent();

            // Foreign key (opsional tapi bagus)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};