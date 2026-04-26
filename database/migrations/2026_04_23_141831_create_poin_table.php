<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poin', function (Blueprint $table) {
            $table->id('poin_id');
            $table->foreignId('nasabah_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksi', 'transaksi_id')->onDelete('cascade');
            $table->integer('jumlah_poin');
            $table->date('tanggal_diberikan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poin');
    }
};