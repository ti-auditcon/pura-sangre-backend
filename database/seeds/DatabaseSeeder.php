<?php

use App\Models\Bills\Bill;
use App\Models\Users\User;
// use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
// use App\Models\Exercises\Stage;
use Illuminate\Database\Seeder;
use App\Models\Users\Millestone;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentType;
use App\Models\Exercises\Exercise;
use App\Models\Clases\Reservation;
use App\Models\Exercises\Statistic;
// use App\Models\Bills\PaymentStatus;
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
      $this->call(StatusUsersTableSeeder::class);
      $this->call(StagesTableSeeder::class);
      $this->call(PlanPeriodsTableSeeder::class);
      $this->call(PlansTableSeeder::class);
      $this->call(PaymentStatusesTableSeeder::class);

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
          // 'emergency_id' => 1,
          'status_user_id' => 1,
          'admin' => 'true',
      ]);

        factory(Emergency::class, 60)->create();
        factory(Millestone::class, 40)->create();
        factory(User::class, 50)->create()->each(function ($u)
        {
          factory(PlanUser::class, 1)->create(['user_id' => $u->id ]);
        });

        factory(PaymentType::class, 4)->create();
        factory(Bill::class, 100)->create();
        // factory(PaymentStatus::class, 4)->create();
        // factory(Installment::class, 200)->create();

        factory(Exercise::class, 30)->create();
        factory(ExerciseStage::class, 40)->create();
        factory(Statistic::class, 5)->create();

        // factory(PlanUser::class, 500)->create();
        $this->call(BlockTableSeeder::class);
        // factory(Clase::class, 5)->create();
        factory(ReservationStatus::class, 3)->create();
        factory(Reservation::class, 300)->create();
        factory(ReservationStatisticStage::class, 400)->create();
    }
}
