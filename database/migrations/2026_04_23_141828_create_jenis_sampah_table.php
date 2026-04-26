<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_sampah', function (Blueprint $table) {
            $table->id('jenis_sampah_id');
            $table->string('nama_jenis');
            $table->text('deskripsi')->nullable();
            $table->integer('harga_sampah');
            $table->string('gambar')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_sampah');
    }
};
