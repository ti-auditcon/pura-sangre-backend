<?php

use App\Models\Users\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
  /*      $user = User::create([
    'rut' => 22222222,
    'first_name' => 'User',
    'last_name' => 'Asomic',
    'birthdate' => '1985-01-02',
    'gender' => 'male',
    'email' => 'user@auditcon.cl',
    'password' => bcrypt('123123'),
    'avatar' => url('/').'/storage/users/u (23).jpg',
    'phone' => '76543211',
    'address' => 'Estado, Esquina Membrillar, Oficina 208',
    // 'emergency_id' => 1,
    'status_user_id' => 1,
]);*/

        // $this->call([
        //     BlockTableSeeder::class,
        //     StatusUsersTableSeeder::class,
        //     PlanPeriodsTableSeeder::class,
        //     PlansTableSeeder::class,
        //     PaymentStatusesTableSeeder::class,
        //     PaymentTypesTableSeeder::class,
        //     RolesTableSeeder::class,
        //     PlanStatusTableSeeder::class,
        //     StageTypesTableSeeder::class,
        //     ReservationStatusesTableSeeder::class,
        //     OauthClientsTableSeeder::class,
        //     RoleUserTableSeeder::class,
        //     UsersTableSeeder::class,
        //     PlanUserTableSeeder::class,
        //     BillsTableSeeder::class,
        //     ReservationsTableSeeder::class,
        // ]);

        $this->call(ClaseTypesTableSeeder::class);
    }
}
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

// echo now()->startOfHour();
// echo "\n";
// echo today();
