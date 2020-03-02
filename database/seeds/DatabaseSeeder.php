<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
         $this->call([
             \ClaseTypesTableSeeder::class,
             \PlansTableSeeder::class,
             \BlockTableSeeder::class,
             \StatusUsersTableSeeder::class,
             \PlanPeriodsTableSeeder::class,
             \PlansTableSeeder::class,
             \PaymentStatusesTableSeeder::class,
             \PaymentTypesTableSeeder::class,
             \RolesTableSeeder::class,
             \PlanStatusTableSeeder::class,
             \StageTypesTableSeeder::class,
             \ReservationStatusesTableSeeder::class,
             \OauthClientsTableSeeder::class,
             \RoleUserTableSeeder::class,
             \UsersTableSeeder::class,
            //  \PlanUserTableSeeder::class,
            //  \BillsTableSeeder::class,
            //  \ReservationsTableSeeder::class,
        ]);
    }
}