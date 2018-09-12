<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/** [CreateClasesTable description] */
class CreateClasesTable extends Migration
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

      Schema::create('blocks', function (Blueprint $table) {
          $table->increments('id');
          $table->string('start');
          $table->string('end');
          $table->unsignedInteger('dow');
          $table->timestamps();
      });

      Schema::create('block_plan', function (Blueprint $table) {
          $table->increments('id');
          $table->unsignedInteger('block_id');
          $table->unsignedInteger('plan_id');
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
        Schema::dropIfExists('clases');
        Schema::dropIfExists('reservation_statuses');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('block_plan');
    }
}
