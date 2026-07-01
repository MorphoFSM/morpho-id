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
    Schema::create('specimens', function (Blueprint $table) {
        $table->id();
        $table->string('nama_spesimen');
        $table->string('kategori');
        $table->string('ciri_ciri')->nullable(); // Boleh kosong kalau takde tag
        $table->string('gambar');                // Untuk simpan laluan fail gambar
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimens');
    }
};
