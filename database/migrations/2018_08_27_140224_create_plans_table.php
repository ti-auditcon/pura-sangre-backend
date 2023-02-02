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
        Schema::create('plan_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('period')->nullable();
            $table->integer('period_number')->nullable();
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan');
            $table->text('description')->nullable();
            $table->unsignedInteger('plan_period_id')->nullable();
            $table->boolean('has_clases');
            $table->integer('class_numbers');
            $table->integer('amount')->nullable();
            $table->boolean('custom')->nullable();
            $table->boolean('convenio')->default(0);
            $table->boolean('contractable')->default(true);
            $table->integer('daily_clases')->default(true);
            $table->integer('plan_status_id')->default(true);
            $table->string('image_path')->nullable();
            
            $table->timestamps();
        });

        // Schema::create('discounts', function (Blueprint $table) {
        //   $table->increments('id');
        //   $table->string('discount')->nullable();
        //   $table->string('percent');
        //   $table->timestamps();
        // });

        Schema::create('plan_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_status')->nullable();
            $table->string('type')->nullable();
            $table->boolean('can_delete')->nullable();
            
            $table->timestamps();
        });

        Schema::create('plan_user', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date');
            $table->dateTime('finish_date');
            $table->integer('counter')->nullable();
            $table->text('observations')->nullable();
            $table->json('history')->nullable();
            $table->unsignedInteger('plan_status_id')->nullable();
            // $table->unsignedInteger('discount_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('plan_status_id')->references('id')->on('plan_status')->onDelete('cascade');
            // // $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            // $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });


        Schema::create('plan_user_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date');
            $table->dateTime('finish_date');
            $table->integer('counter')->nullable();
            $table->unsignedInteger('plan_status_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->integer('paid')->default(false);
            $table->integer('amount')->nullable();
            $table->string('observations')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('bill_pdf')->nullable();
            $table->string('sii_token')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('freeze_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_user_id');
            $table->date('start_date');
            $table->date('finish_date');
            $table->unsignedTinyInteger('revoked')->default(false);
            $table->unsignedInteger('days');
            $table->timestamps();
        });

        Schema::create('plan_income_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_id')->nullable();
            $table->integer('amount');
            $table->integer('quantity');
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            
            $table->timestamps();

            // $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('plans');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('plan_status');
        Schema::dropIfExists('plan_user');
        Schema::dropIfExists('plan_periods');
        Schema::dropIfExists('plan_user_flows');
        Schema::dropIfExists('freeze_plans');
        Schema::dropIfExists('plan_income_summaries');
    }
}
        // Schema::create('payments', function (Blueprint $table) {
        //     $table->increments('id');
        //
        //     $table->timestamps();
        //     $table->softDeletes();
        //
        // });

        // Schema::create('installments', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->unsignedInteger('bill_id');
        //     $table->unsignedInteger('payment_status_id');
        //     $table->unsignedInteger('plan_user_id');
        //     $table->date('commitment_date')->nullable();
        //     $table->date('payment_date')->nullable();
        //     $table->date('expiration_date');
        //     $table->integer('amount');
        //     $table->timestamps();
        //     $table->softDeletes();
        //
        //     $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
        //     $table->foreign('payment_status_id')->references('id')->on('payment_statuses')->onDelete('cascade');
        //     $table->foreign('plan_user_id')->references('id')->on('plan_user')->onDelete('cascade');
        // });
