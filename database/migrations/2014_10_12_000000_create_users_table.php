<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * [CreateUsersTable description]
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencies', function (Blueprint $table) {
          $table->increments('id');
          $table->string('contact_name');
          $table->integer('contact_phone');
          $table->timestamps();
        });

        Schema::create('status_users', function (Blueprint $table) {
          $table->increments('id');
          $table->string('status_user');
          $table->timestamps();
        });

        Schema::create('millestones', function (Blueprint $table) {
          $table->increments('id');
          $table->string('millestone');
          $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedInteger('emergency_id')->nullable();
            $table->unsignedInteger('status_user_id');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('emergency_id')->references('id')->on('emergencies')->onDelete('cascade');
            $table->foreign('status_user_id')->references('id')->on('status_users')->onDelete('cascade');
        });

        Schema::create('millestone_users', function (Blueprint $table) {
          $table->unsignedInteger('millestone_id')->nullable();
          $table->unsignedInteger('user_id');
          $table->date('month');
          $table->date('year');
          $table->timestamps();
          $table->softDeletes();

          $table->foreign('millestone_id')->references('id')->on('millestones')->onDelete('cascade');
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
        Schema::dropIfExists('emergencies');
        Schema::dropIfExists('status_users');
        Schema::dropIfExists('millestones');
        Schema::dropIfExists('users');
        Schema::dropIfExists('millestone_users');
    }
}
