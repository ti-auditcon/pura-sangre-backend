<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * [CreatePlansTable description]
 */
class CreatePlansTable extends Migration
{
    /**
     * [up description]
     * @method up
     * @return [void] [description]
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan');
            $table->integer('class_numbers');
            $table->timestamps();
        });

        Schema::create('discounts', function (Blueprint $table) {
          $table->increments('id');
          $table->string('discount')->nullable();
          $table->string('percent');
          $table->timestamps();
        });

        Schema::create('plan_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start_time');
            $table->string('finish_time');
            $table->unsignedInteger('discount_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('plans');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('plan_user');
    }
}
