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
        Schema::create('absensi_datangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->time('absensi_datang_time');
            $table->date('absensi_datang_date');
            $table->enum('status_datang',['alpa','sakit','masuk'])->nullable();
            $table->string('qr_code_datang')->nullable();
            $table->string('longitude_datang')->nullable();
            $table->string('latitude_datang')->nullable();
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
        Schema::dropIfExists('absensi_datangs');
    }
};
