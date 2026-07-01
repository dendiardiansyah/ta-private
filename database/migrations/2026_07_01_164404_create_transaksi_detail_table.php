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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id('transaksi_detail_id');
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('jenis_sampah_id');
            $table->decimal('berat', 8, 2); // Max 999,999.99 kg with 2 decimal places
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('transaksi_id')
                  ->references('transaksi_id')
                  ->on('transaksi')
                  ->onDelete('cascade');
            
            $table->foreign('jenis_sampah_id')
                  ->references('jenis_sampah_id')
                  ->on('jenis_sampah')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
