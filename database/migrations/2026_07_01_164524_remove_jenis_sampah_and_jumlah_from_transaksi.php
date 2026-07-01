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
        Schema::table('transaksi', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['jenis_sampah_id']);
            
            // Then drop the columns
            $table->dropColumn(['jenis_sampah_id', 'jumlah']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_sampah_id')->nullable()->after('petugas_id');
            $table->decimal('jumlah', 10, 2)->nullable()->after('alamat_penjemputan');
            
            $table->foreign('jenis_sampah_id')
                  ->references('jenis_sampah_id')
                  ->on('jenis_sampah')
                  ->onDelete('restrict');
        });
    }
};
