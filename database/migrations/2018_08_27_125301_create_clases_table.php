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
        Schema::create('reservation_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reservation_status');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('clase_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clase_type');
            $table->string('clase_color');
            $table->string('icon')->nullable();
            $table->string('icon_white')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedTinyInteger('special')->default(false);
            $table->timestamps();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start');
            $table->time('end');
            $table->string('title')->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('coach_id')->nullable();
            $table->Integer('quota')->nullable();
            $table->unsignedInteger('clase_type_id')->nullable();
            $table->unsignedInteger('dow')->nullable();
            $table->timestamps();

          // $table->foreign('coach_id')->references('id')->on('users')->onDelete('cascade');
          // $table->foreign('clase_type_id')->references('id')->on('clase_types')->onDelete('cascade');
        });

        Schema::create('clases', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date')->index();
            $table->time('start_at')->nullable();
            $table->time('finish_at')->nullable();
            $table->unsignedInteger('block_id')->nullable();
            $table->integer('room')->nullable();
            $table->integer('coach_id')->nullable();
            $table->integer('wod_id')->nullable();
            $table->integer('quota')->nullable();
            $table->unsignedInteger('clase_type_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

          // $table->foreign('coach_id')->references('id')->on('users')->onDelete('cascade');
          // $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_user_id')->nullable();
            $table->unsignedInteger('clase_id');
            $table->unsignedInteger('reservation_status_id');
            $table->unsignedInteger('user_id');
            $table->boolean('by_god')->nullable();
            $table->longText('details')->nullable();
            $table->timestamps();

          // $table->foreign('clase_id')->references('id')->on('clases')->onDelete('cascade');
          // $table->foreign('reservation_status_id')->references('id')->on('reservation_statuses')
          // ->onDelete('cascade');
          // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('block_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('block_id');
            $table->unsignedInteger('plan_id');
            $table->timestamps();

          // $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
          // $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
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
        Schema::dropIfExists('clase_types');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('clases');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('block_plan');
    }
}
