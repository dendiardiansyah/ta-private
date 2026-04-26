<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaku_usaha', function (Blueprint $table) {
            $table->id('pelaku_usaha_id');
            $table->string('nama');
            $table->string('password');
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaku_usaha');
    }
};
