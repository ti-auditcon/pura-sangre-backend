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

      Schema::create('reservation_statuses', function (Blueprint $table) {
          $table->increments('id');
          $table->string('reservation_status');
          $table->timestamps();
      });

      Schema::create('block_types', function (Blueprint $table) {
          $table->increments('id');
          $table->string('block_type');
          $table->integer('max_quota');
          $table->timestamps();
      });

      Schema::create('blocks', function (Blueprint $table) {
          $table->increments('id');
          $table->string('start');
          $table->string('end');
          $table->string('title')->nullable();
          $table->date('date')->nullable();
          $table->unsignedInteger('profesor_id')->nullable();
          $table->unsignedInteger('block_type_id')->nullable();
          $table->unsignedInteger('dow')->nullable();
          $table->timestamps();

          $table->foreign('profesor_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('block_type_id')->references('id')->on('block_types')->onDelete('cascade');
      });

      Schema::create('clases', function (Blueprint $table) {
          $table->increments('id');
          $table->date('date');
          $table->time('start_at')->nullable();
          $table->time('finish_at')->nullable();
          $table->unsignedInteger('block_id')->nullable();
          $table->integer('room')->nullable();
          $table->integer('profesor_id')->nullable();
          $table->integer('quota')->nullable();
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
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
        Schema::dropIfExists('reservation_statuses');
        Schema::dropIfExists('block_types');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('clases');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('block_plan');
    }
}
