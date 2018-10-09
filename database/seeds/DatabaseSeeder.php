<?php

use App\Models\Bills\Bill;
use App\Models\Bills\Installment;
use App\Models\Clases\ClaseStage;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatisticStage;
use App\Models\Clases\ReservationStatus;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\ExerciseStage;
use App\Models\Exercises\Stage;
use App\Models\Exercises\Statistic;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use App\Models\Users\Millestone;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

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
      $this->call(PlanPeriodsTableSeeder::class);
      $this->call(PlansTableSeeder::class);
      $this->call(PaymentStatusesTableSeeder::class);
      $this->call(PaymentTypesTableSeeder::class);
      $this->call(ExercisesTableSeeder::class);
      $this->call(ExerciseStagesTableSeeder::class);
      $this->call(RolesTableSeeder::class);
      $this->call(PlanStatusTableSeeder::class);
      $this->call(StageTypesTableSeeder::class);
      factory(Emergency::class, 60)->create();

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
          'emergency_id' => 1,
          'status_user_id' => 1,
      ]);

      $user = User::create([
          'rut' => 22222222,
          'first_name' => 'User',
          'last_name' => 'Asomic',
          'birthdate' => '1985-01-02',
          'gender' => 'male',
          'email' => 'user@auditcon.cl',
          'password' => bcrypt('123123'),
          'phone' => '76543211',
          'address' => 'Estado, Esquina Membrillar, Oficina 208',
          'emergency_id' => 1,
          'status_user_id' => 1,
      ]);

        factory(User::class, 50)->create()->each(function ($u)
        {
          factory(PlanUser::class, 3)->create(['user_id' => $u->id ]);
        });
        factory(Stage::class, 200)->create();
        $this->call(BlockTableSeeder::class);
        factory(ClaseStage::class, 600)->create();
        factory(Millestone::class, 40)->create();

        factory(Bill::class, 100)->create();
        factory(Statistic::class, 5)->create();
        factory(ReservationStatus::class, 3)->create();
        factory(Reservation::class, 2000)->create();
        $this->call(RoleUserTableSeeder::class);
    }
}
