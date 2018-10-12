<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * [CreateExercisesTable description]
 */
class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('exercises', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('exercise');
        //     $table->timestamps();
        // });

        Schema::create('stage_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stage_type');
            $table->timestamps();
        });

        Schema::create('stages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stage_type_id')->nullable();
            $table->unsignedInteger('wod_id')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('star')->nullable();
            $table->timestamps();

            // $table->foreign('stage_type_id')->references('id')->on('stage_types')->onDelete('cascade');
        });

        // Schema::create('exercise_stage', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('exercise_id');
        //     $table->unsignedInteger('stage_id');
        //     $table->timestamps();
        //     $table->softDeletes();
        //
        //     $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
        //     $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        // });

        Schema::create('wods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('clase_type_id');
            $table->date('date');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('clase_type_id')->references('id')->on('clase_types')->onDelete('cascade');
        });

        // Schema::create('statistics', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('statistic');
        //     $table->timestamps();
        // });

        // Schema::create('rsvn_stat_excses_stages', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('reservation_id');
        //     $table->unsignedInteger('statistic_id');
        //     $table->unsignedInteger('exercise_stage_id');
        //     $table->string('weight')->nullable();
        //     $table->integer('time')->nullable();
        //     $table->integer('repetitions')->nullable();
        //     $table->integer('round')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->foreign('statistic_id')->references('id')->on('statistics')->onDelete('cascade');
        //     $table->foreign('exercise_stage_id')->references('id')->on('exercise_stages')->onDelete('cascade');
        //     $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::disableForeignKeyConstraints();
      Schema::dropIfExists('stage_types');
      Schema::dropIfExists('stages');
      Schema::dropIfExists('wods');
      // Schema::dropIfExists('exercises');

      // Schema::dropIfExists('exercise_stage');
      // Schema::dropIfExists('clase_stage');

      // Schema::dropIfExists('statistics');
      // Schema::dropIfExists('reservation_statistic_stages');
    }
}
        // Schema::dropIfExists('clase_exercise_stages');
        // Schema::create('clase_exercise_stages', function (Blueprint $table) {
        //   $table->unsignedInteger('clase_id');
        //   $table->unsignedInteger('exercise_stage_id');
        //   $table->unsignedInteger('stage_id');
        //   $table->string('name');
        //   $table->longText('description');
        //   $table->timestamps();

        //   $table->foreign('clase_id')->references('id')->on('clases')->onDelete('cascade');
        //   $table->foreign('exercise_stage_id')->references('id')->on('exercise_stages')->onDelete('cascade');
        //   $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        // });
