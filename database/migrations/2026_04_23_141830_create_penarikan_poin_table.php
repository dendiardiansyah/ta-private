<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penarikan_poin', function (Blueprint $table) {
            $table->id('penarikan_poin_id');
            $table->foreignId('nasabah_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah_poin');
            $table->integer('jumlah_uang');
            $table->string('status_penarikan')->default('Dalam Proses');
            $table->date('tanggal_penarikan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penarikan_poin');
    }
};
