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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->string('userid');
            $table->string('email');
            $table->string('role');
            $table->string('status'); // 'Berjaya' atau 'Gagal'
            $table->string('note')->nullable(); // Contoh: 'Katalaluan Salah'
            $table->timestamp('created_at'); // Guna ni sebagai Tarikh & Masa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
