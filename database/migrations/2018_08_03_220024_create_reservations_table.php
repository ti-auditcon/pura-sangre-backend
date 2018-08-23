<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * [CreateReservationsTable description]
 */
class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Schema::drop('classes');

      Schema::create('clases', function (Blueprint $table) {
          $table->increments('id');
          $table->string('date');
          $table->string('start_at');
          $table->string('finish_at');
          $table->integer('room')->nullable();
          $table->integer('profesor_id');
          $table->integer('quota');
          $table->timestamps();
          // $table->softDeletes();
      });

      Schema::create('clase_stage', function (Blueprint $table) {
        $table->unsignedInteger('clase_id');
        $table->unsignedInteger('stage_id');
        $table->timestamps();

        $table->foreign('clase_id')->references('id')->on('clases')->onDelete('cascade');
        $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
      });

      Schema::create('reservation_statuses', function (Blueprint $table) {
          $table->increments('id');
          $table->string('reservation_status');
          $table->timestamps();
      });

      Schema::create('reservations', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('clase_id');
        $table->unsignedInteger('reservation_status_id');
        $table->unsignedInteger('user_id');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('clase_id')->references('id')->on('clases')->onDelete('cascade');
        $table->foreign('reservation_status_id')->references('id')->on('reservation_statuses')
        ->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      });

      Schema::create('reservation_statistic_stages', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('statistic_id');
          $table->unsignedInteger('reservation_id');
          $table->unsignedInteger('exercise_stage_id');
          $table->string('weight')->nullable();
          $table->time('time')->nullable();
          $table->integer('round')->nullable();
          $table->integer('repetitions')->nullable();
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('statistic_id')->references('id')->on('statistics')->onDelete('cascade');
          $table->foreign('exercise_stage_id')->references('id')->on('exercise_stages')->onDelete('cascade');
          $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('clases');
        Schema::dropIfExists('reservation_statistic_stages');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('reservation_statuses');
        Schema::dropIfExists('clase_stage');
    }
}
