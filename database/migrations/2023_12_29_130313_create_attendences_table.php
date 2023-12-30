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
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->time('attendences_time');
            $table->date('attendences_date');
            $table->enum('attendance_status', ['hadir', 'alfa', 'sakit'])->default('hadir');
            $table->enum('attendance_type', ['onsite', 'online'])->default('onsite')->nullable();
            $table->enum('attendences_Status', ['pending_approval', 'accepted', 'rejected'])->default('pending_approval');
            $table->string('file')->nullable();
            $table->string('longitude_attendences')->nullable();
            $table->string('latitude_attendences')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('attendences');
    }
};
