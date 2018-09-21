<?php

use App\Models\Bills\Bill;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use App\Models\Exercises\Stage;
use Illuminate\Database\Seeder;
use App\Models\Users\Millestone;
use App\Models\Bills\Installment;
use App\Models\Exercises\Exercise;
use App\Models\Clases\Reservation;
use App\Models\Exercises\Statistic;
use App\Models\Exercises\ExerciseStage;
use App\Models\Clases\ReservationStatus;
use App\Models\Clases\ReservationStatisticStage;

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
      $this->call(PaymentStatusesTableSeeder::class);
      $this->call(PaymentTypesTableSeeder::class);
      $this->call(StatusUsersTableSeeder::class);
      $this->call(StagesTableSeeder::class);
      $this->call(PlanPeriodsTableSeeder::class);
      $this->call(PlansTableSeeder::class);

      $user = User::create([
              'rut' => 11111111,
              'first_name' => 'Audito',
              'last_name' => 'Asomic',
              'birthdate' => '1985-01-01',
              'gender' => 'male',
              'email' => 'sa@auditcon.cl',
              'password' => bcrypt('123123'),
              'phone' => '87654321',
              'address' => 'Estado, Esquina Membrillar, Oficina 208',
              'status_user_id' => 1,
              'remember_token' => str_random(10),
              'admin' => 'true',
              ]);

        factory(Emergency::class, 60)->create();
        factory(Millestone::class, 40)->create();
        factory(Bill::class, 100)->create();
        factory(User::class, 50)->create()->each(function ($u){
          factory(PlanUser::class, 1)->create(['user_id' => $u->id ])->each(function ($pu){
            factory(Installment::class, 1)->create(['plan_user_id' => $pu->id]);
          });
        });

        factory(Exercise::class, 30)->create();
        factory(ExerciseStage::class, 40)->create();
        factory(Statistic::class, 5)->create();

        factory(Clase::class, 100)->create()->each(
          function ($class) {
            $stages = Stage::all()->random(mt_rand(1, 3))->pluck('id');
            $class->stages()->attach($stages);
          });
        factory(ReservationStatus::class, 3)->create();
        factory(Reservation::class, 300)->create();
        factory(ReservationStatisticStage::class, 400)->create();
    }
}

// factory(PaymentType::class, 4)->create();
// factory(PaymentStatus::class, 4)->create();
// factory(Installment::class, 200)->create();
// factory(PlanUser::class, 500)->create();
// factory(Clase::class, 5)->create();
