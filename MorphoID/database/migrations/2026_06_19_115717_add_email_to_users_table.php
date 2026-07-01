<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah lajur email ke dalam jadual users sedia ada
        Schema::table('users', function (Blueprint $table) {
            // Kita letak nullable() dulu supaya tak crash kalau jadual kau dah ada data dummy sebelum ni
            $table->string('email')->unique()->after('name')->nullable();
        });
    }

    public function down(): void
    {
        // Buang lajur email kalau kita rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
