<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyTrialUserReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_trial_user_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('year');
            $table->integer('month');
            $table->integer('plans_sold')->nullable();
            $table->integer('trial_users')->nullable();
            $table->integer('trial_classes_consumed')->nullable();
            $table->decimal('trial_classes_taken_percentage', 5, 2)->nullable();
            $table->integer('trial_conversion')->nullable();
            $table->decimal('trial_conversion_percentage', 5, 2)->nullable();
            $table->decimal('trial_retention_percentage', 5, 2)->nullable();
            $table->integer('inactive_users')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_trial_user_reports');
    }
}
