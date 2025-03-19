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
        Schema::create('bebas', function (Blueprint $table) {
            $table->id();
            $table->double('bill');
            $table->double('total_pay');
            $table->foreignId('siswa_id')->constrained('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayaran')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bebas');
    }
};
