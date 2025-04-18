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
        Schema::create('bebas_pay', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bebas_id')->constrained('bebas')->onUpdate('cascade')->onDelete('cascade');
            $table->string('number_pay');
            $table->date('tanggal');
            $table->double('pay_bill');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bebas_pay');
    }
};
