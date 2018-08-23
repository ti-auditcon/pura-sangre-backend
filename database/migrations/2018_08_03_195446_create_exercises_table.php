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
        Schema::create('exercises', function (Blueprint $table) {
            $table->increments('id');
            $table->string('exercise');
            $table->timestamps();
        });

        Schema::create('stages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stage');
            $table->timestamps();
        });

        Schema::create('exercise_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exercise_id');
            $table->unsignedInteger('stage_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        });

        Schema::create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('statistic');
            $table->timestamps();
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
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('exercise_stages');
        Schema::dropIfExists('statistics');
    }
}
