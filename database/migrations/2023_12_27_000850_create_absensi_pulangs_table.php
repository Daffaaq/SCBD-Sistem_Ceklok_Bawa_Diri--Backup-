<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi_pulangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
             $table->time('absensi_pulang_time')->nullable(); // Tambahkan nullable agar bisa diisi null
            $table->date('absensi_pulang_date')->nullable(); // Tambahkan nullable agar bisa diisi null
            $table->string('qr_code_pulang')->nullable();
            $table->string('longitude_pulang')->nullable();
            $table->string('latitude_pulang')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi_pulangs');
    }
};
