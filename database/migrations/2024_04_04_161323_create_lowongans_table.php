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
        Schema::create('lowongans', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('nama_posisi');
            $table->longText('deskripsi_pekerjaan');
            $table->longText('kualifikasi');
            $table->string('lokasi');
            $table->boolean('open');
            $table->integer('slot_posisi');
            $table->integer('gaji_dari');
            $table->integer('gaji_hingga');
            $table->uuid('id_perusahaan');
            $table->foreign('id_perusahaan')->references('id')->on('perusahaans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
