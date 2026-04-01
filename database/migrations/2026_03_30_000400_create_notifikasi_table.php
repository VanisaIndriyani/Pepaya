<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnDelete();
            $table->foreignId('jadwal_perawatan_id')->nullable()->constrained('jadwal_perawatan')->nullOnDelete();
            $table->string('nomor');
            $table->text('pesan');
            $table->dateTime('tanggal_kirim');
            $table->string('status');
            $table->longText('response')->nullable();
            $table->timestamps();

            $table->index(['tanaman_id', 'tanggal_kirim']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
