<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->foreignId('nasabah_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah', 'jenis_sampah_id')->onDelete('cascade');
            $table->foreignId('pelaku_usaha_id')->nullable()->constrained('pelaku_usaha', 'pelaku_usaha_id')->onDelete('cascade');
            $table->text('alamat_penjemputan');
            $table->integer('jumlah');
            $table->date('tanggal_transaksi');
            $table->string('status')->default('menunggu penjemputan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};