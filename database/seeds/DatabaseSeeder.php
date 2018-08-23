<?php

use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use App\Models\Exercises\Stage;
use Illuminate\Database\Seeder;
use App\Models\Users\Millestone;
use App\Models\Users\StatusUser;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentType;
use App\Models\Reservations\Clase;
use Illuminate\Support\Facades\DB;
use App\Models\Exercises\Exercise;
use App\Models\Bills\PaymentStatus;
use App\Models\Exercises\Statistic;
use App\Models\Exercises\ExerciseStage;
use App\Models\Reservations\Reservation;
use App\Models\Reservations\ReservationStatus;
use App\Models\Reservations\ReservationStatisticStage;

/**
 * [DatabaseSeeder description]
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // User::truncate();
        // DB::table('emergencies')->truncate();
        // DB::table('status_users')->truncate();
        // DB::table('millestones')->truncate();
        // DB::table('millestone_users')->truncate();

        // Bill::truncate();
        // DB::table('payment_type')->truncate();
        // DB::table('payment_status')->truncate();
        // DB::table('installments')->truncate();

        // Exercise::truncate();
        // DB::table('stages')->truncate();
        // DB::table('exercise_stage')->truncate();
        // DB::table('statistics')->truncate();

        // Plan::truncate();
        // DB::table('discounts')->truncate();
        // DB::table('plan_users')->truncate();

        // Reservation::truncate();
        // DB::table('Class')->truncate();
        // DB::table('class_stage')->truncate();
        // DB::table('reservation_status')->truncate();
        // DB::table('exercise_reservation_stages')->truncate();

        factory(Emergency::class, 60)->create();
        factory(StatusUser::class, 4)->create();
        factory(Millestone::class, 40)->create();
        factory(User::class, 50)->create();

        factory(PaymentType::class, 4)->create();
        factory(Bill::class, 50)->create();
        factory(PaymentStatus::class, 4)->create();
        factory(Installment::class, 100)->create();

        factory(Exercise::class, 30)->create();
        factory(Stage::class, 3)->create();
        factory(ExerciseStage::class, 40)->create();
        factory(Statistic::class, 5)->create();

        factory(Plan::class, 5)->create();
        factory(PlanUser::class, 100)->create();

        factory(Clase::class, 5)->create();
        factory(Clase::class, 100)->create()->each(
          function ($class) {
            $stages = Stage::all()->random(mt_rand(1, 3))->pluck('id');
            $class->stages()->attach($stages);
        });
        factory(ReservationStatus::class, 3)->create();
        factory(Reservation::class, 100)->create();
        factory(ReservationStatisticStage::class, 100)->create();



    }
}
