<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_tanaman')->default('Pepaya');
            $table->date('tanggal_tanam');
            $table->date('estimasi_panen');
            $table->decimal('luas_lahan', 12, 2);
            $table->string('lokasi');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanaman');
    }
};
