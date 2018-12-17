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
    //  $this->call(BlockTableSeeder::class);
      $this->call(StatusUsersTableSeeder::class);
      $this->call(PlanPeriodsTableSeeder::class);
      $this->call(PlansTableSeeder::class);
      $this->call(PaymentStatusesTableSeeder::class);
      $this->call(PaymentTypesTableSeeder::class);
      $this->call(RolesTableSeeder::class);
      $this->call(PlanStatusTableSeeder::class);
      $this->call(StageTypesTableSeeder::class);
      $this->call(ReservationStatusesTableSeeder::class);
      //$this->call(OauthClientsTableSeeder::class);
      //$this->call(RoleUserTableSeeder::class);
      $this->call(UsersTableSeeder::class);

    //$this->call(PlanUserTableSeeder::class);
      //$this->call(BillsTableSeeder::class);
      //$this->call(ReservationsTableSeeder::class);
    }
}
