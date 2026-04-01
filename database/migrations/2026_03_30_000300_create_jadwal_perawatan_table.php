<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_perawatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanaman_id')->constrained('tanaman')->cascadeOnDelete();
            $table->string('jenis');
            $table->date('tanggal');
            $table->string('status')->default('pending');
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->index(['tanaman_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_perawatan');
    }
};
