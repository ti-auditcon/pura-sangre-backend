<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyStudentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_student_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('year');
            $table->integer('month');
            $table->integer('active_students_start')->nullable();
            $table->integer('active_students_end')->nullable();
            $table->integer('dropouts')->nullable();
            $table->integer('new_students')->nullable();
            $table->decimal('dropout_percentage', 5, 2)->nullable();
            $table->decimal('new_students_percentage', 5, 2)->nullable();
            $table->integer('turnaround')->nullable();
            $table->integer('previous_month_difference')->nullable();
            $table->decimal('growth_rate', 5, 2)->nullable();
            $table->decimal('retention_rate', 5, 2)->nullable();
            $table->decimal('rotation', 5, 2)->nullable();
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
        Schema::dropIfExists('monthly_student_reports');
    }
}