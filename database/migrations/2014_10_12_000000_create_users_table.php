<?php

use App\Models\Users\User;
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
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('message');
            $table->date('from');
            $table->date('to');
            $table->timestamps();
        });
        
        Schema::create('status_users', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('status_user');
            
            $table->string('type')->nullable();
            
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('role');
            
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rut');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->unsignedInteger('phone')->nullable();
            $table->date('birthdate');
            $table->date('since')->nullable();
            $table->string('gender');
            $table->string('address')->nullable();
            $table->unsignedInteger('status_user_id')->nullable();
            $table->longText('fcm_token')->nullable();
            $table->boolean('tutorial')->default(false);
            // $table->unsignedInteger('emergency_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('emergency_id')->references('id')->on('emergencies')->onDelete('cascade');
        });

        // contactos de emergencia
        Schema::create('emergencies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('contact_name')->nullable();
            $table->integer('contact_phone')->nullable();
            $table->timestamps();

          // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

          // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
          // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('sended')->default(0);
            $table->timestamp('trigger_at');
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

        Schema::dropIfExists('alerts');
        
        Schema::dropIfExists('status_users');
        
        Schema::dropIfExists('roles');
        
        Schema::dropIfExists('users');
        
        Schema::dropIfExists('emergencies');
        
        Schema::dropIfExists('role_user');
        
        Schema::dropIfExists('notifications');
    }
}
