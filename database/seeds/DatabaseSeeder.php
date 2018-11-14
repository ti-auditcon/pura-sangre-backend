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
      $this->call(BlockTableSeeder::class);
      $this->call(StatusUsersTableSeeder::class);
      $this->call(PlanPeriodsTableSeeder::class);
      $this->call(PlansTableSeeder::class);
      $this->call(PaymentStatusesTableSeeder::class);
      $this->call(PaymentTypesTableSeeder::class);
      $this->call(RolesTableSeeder::class);
      $this->call(PlanStatusTableSeeder::class);
      $this->call(StageTypesTableSeeder::class);
      $this->call(ReservationStatusesTableSeeder::class);

      $user = User::create([
          'rut' => 11111111,
          'first_name' => 'Audito',
          'last_name' => 'Asomic',
          'birthdate' => '1985-01-01',
          'gender' => 'male',
          'email' => 'sa@auditcon.cl',
          'password' => bcrypt('123123'),
          'phone' => '87654321',
          'avatar' => 'u (22)',
          'address' => 'Estado, Esquina Membrillar, Oficina 208',
          // 'emergency_id' => 1,
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
          'avatar' => 'u (23)',
          'phone' => '76543211',
          'address' => 'Estado, Esquina Membrillar, Oficina 208',
          // 'emergency_id' => 1,
          'status_user_id' => 1,
      ]);

      $user = User::create([
          'rut' => 33333333,
          'first_name' => 'Student',
          'last_name' => 'Crossfit',
          'birthdate' => '1994-01-02',
          'gender' => 'male',
          'email' => 'student@auditcon.cl',
          'password' => bcrypt('123123'),
          'avatar' => 'u (25)',
          'phone' => '76543211',
          'address' => 'Estado, Esquina Membrillar, Oficina 208',
          // 'emergency_id' => 1,
          'status_user_id' => 1,
      ]);

      $this->call(PlanUserTableSeeder::class);
      $this->call(RoleUserTableSeeder::class);
      factory(Stage::class, 200)->create();

      factory(User::class, 50)->create()->each(function ($u)
      {
          factory(PlanUser::class, 10)->create(['user_id' => $u->id ])->each(function ($pu){
            // $bill = new Bill;
            // $bill->plan_user_id = $pu->id;
            // $bill->plan_user_id = $pu->id
            // $bill->plan_user_id = $pu->id
            // $bill->plan_user_id = $pu->id
            //
           if($pu->id!=null){
             factory(Bill::class, 1)->create([
               'plan_user_id' => $pu->id,
               'date' => $pu->start_date,
               'start_date' => $pu->start_date,
               'finish_date' => $pu->finish_date,
               'amount' => $pu->plan->amount,
             ]);
           }


          });

          factory(Reservation::class, 20)->create(['user_id' => $u->id ]);

          //factory(Reservation::class, 20)->create(['user_id' => $u->id ]);
      });
      // $this->call(ReservationsTableSeeder::class);
      // factory(Reservation::class, 2000)->create();

    }
}
