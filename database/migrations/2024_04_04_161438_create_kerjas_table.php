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
        Schema::create('kerjas', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('id_lowongan');
            $table->foreign('id_lowongan')->references('id')->on('lowongans')->onDelete('cascade');
            $table->uuid('id_perusahaan');
            $table->foreign('id_perusahaan')->references('id')->on('perusahaans')->onDelete('cascade');
            $table->uuid('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->string('nama_posisi');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjas');
    }
};
