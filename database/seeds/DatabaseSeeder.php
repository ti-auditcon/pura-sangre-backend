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
      $this->call(OauthClientsTableSeeder::class);

      // $user = User::create([
      //     'rut' => 11111111,
      //     'first_name' => 'Audito',
      //     'last_name' => 'Asomic',
      //     'birthdate' => '1985-01-01',
      //     'gender' => 'male',
      //     'email' => 'sa@auditcon.cl',
      //     'password' => bcrypt('123123'),
      //     'phone' => '87654321',
      //     'avatar' => url('/').'/storage/users/u (22).jpg',
      //     'address' => 'Estado, Esquina Membrillar, Oficina 208',
      //     // 'emergency_id' => 1,
      //     'status_user_id' => 1,
      // ]);

      // $user = User::create([
      //     'rut' => 22222222,
      //     'first_name' => 'User',
      //     'last_name' => 'Asomic',
      //     'birthdate' => '1985-01-02',
      //     'gender' => 'male',
      //     'email' => 'user@auditcon.cl',
      //     'password' => bcrypt('123123'),
      //     'avatar' => url('/').'/storage/users/u (23).jpg',
      //     'phone' => '76543211',
      //     'address' => 'Estado, Esquina Membrillar, Oficina 208',
      //     // 'emergency_id' => 1,
      //     'status_user_id' => 1,
      // ]);

      // $user = User::create([
      //     'rut' => 33333333,
      //     'first_name' => 'Student',
      //     'last_name' => 'Crossfit',
      //     'birthdate' => '1994-01-02',
      //     'gender' => 'male',
      //     'email' => 'student@auditcon.cl',
      //     'password' => bcrypt('123123'),
      //     'avatar' => url('/').'/storage/users/u (25).jpg',
      //     'phone' => '76543211',
      //     'address' => 'Estado, Esquina Membrillar, Oficina 208',
      //     // 'emergency_id' => 1,
      //     'status_user_id' => 1,
      // ]);

      $this->call(RoleUserTableSeeder::class);
      $this->call(UsersTableSeeder::class);
      // factory(User::class, 200)->create();

      // factory(User::class, 250)->create()->each(function ($u){
         // factory(PlanUser::class, 40)->create(['user_id' => $u->id ])->each(function ($pu){
         //    if($pu->id && $pu->plan->custom == 0){
         //       factory(Bill::class, 1)->create([
         //          'plan_user_id' => $pu->id,
         //          'date' => $pu->start_date,
         //          'start_date' => $pu->start_date,
         //          'finish_date' => $pu->finish_date,
         //          'amount' => $pu->plan->amount,
         //       ]);
         //        $this->call(UsersTableSeeder::class);
    // }
         // });
         // factory(Reservation::class, 10)->create(['user_id' => $u->id ]);
      // });
      $this->call(PlanUserTableSeeder::class);
      $this->call(BillsTableSeeder::class);
      // foreach (User::all() as $user) {
      //     factory(Reservation::class, 100)->create(['user_id' => $user->id ]);
      // }
      $this->call(ReservationsTableSeeder::class);
      // factory(Reservation::class, 1)->create(['user_id' => $user->id ]);
    }
}
