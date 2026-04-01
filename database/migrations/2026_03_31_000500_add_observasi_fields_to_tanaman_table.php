<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tanaman', function (Blueprint $table) {
            $table->date('tanggal_pindah_lahan')->nullable()->after('tanggal_tanam');
            $table->string('panen_mode')->default('normal')->after('status');
            $table->date('panen_mulai')->nullable()->after('estimasi_panen');
            $table->date('panen_sampai')->nullable()->after('panen_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('tanaman', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pindah_lahan', 'panen_mode', 'panen_mulai', 'panen_sampai']);
        });
    }
};
