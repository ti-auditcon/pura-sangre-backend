<?php

namespace Tests\Feature\Bills;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Bills\Bill;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Models\Users\RoleUser;
use App\Models\Clases\ClaseType;
use App\Models\Bills\PaymentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BillControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     *  A created admin for tests
     *
     * @var  User
     */
    protected $admin;

    /**
     *  Before the tests are executed
     *
     * @return  void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createAnAdminUser();

        $birthdate_users = app(User::class)->birthdate_users();

        view()->share(compact('birthdate_users'));

        ClaseType::create([
            'clase_type' => 'CrossFit',
            'clase_color' => 'CrossFit',
            'icon' => 'crossfit.svg',
            'icon_white' => 'crossfit.svg',
            'active' => true,
        ]);
    }

    /**
     *  Manage all the requirements to create a Admin for tests
     *
     *  @return  void
     */
    public function createAnAdminUser(): void
    {
        $user = factory(User::class)->create();
        $this->createAdminRole();
        $this->makeUserAnAdmin($user);
        $this->admin = $user;
    }

    /**
     *  @return  void
     */
    public function createAdminRole(): void
    {
        factory(Role::class)->create(['role' => 'admin']);
    }

    /**
     *  @param   User  $user
     */
    protected function makeUserAnAdmin($user)
    {
        factory(RoleUser::class)->create(['user_id' => $user->id, 'role_id' => Role::ADMIN]);
    }
    
    /** @test */
    public function store_bill_has_validations()
    {
        $this->actingAs($this->admin)
                ->post(route('payments.store'), [])
                ->assertStatus(302)
                ->assertSessionHasErrors([
                    'plan_user_id', 'payment_type_id', 'plan_user_id',
                    'date', 'amount'
                ])
                ->assertSessionDoesntHaveErrors(['detail']);
    }

    /** @test */
    public function it_stores_a_new_payment()
    {
        $payment = factory(Bill::class)->make();

        $this->followingRedirects()
                ->actingAs($this->admin)
                ->post(route('payments.store'), $payment->toArray())
                ->assertStatus(200);

        $this->assertDatabaseHas('bills', [
            'start_date'      => $payment->start_date,
            'finish_date'     => $payment->finish_date,
            'plan_user_id'    => $payment->plan_user_id,
            'amount'          => $payment->amount,
            'payment_type_id' => $payment->payment_type_id,
            'date'            => $payment->date,
            'detail'          => $payment->detail,
        ]);
    }
    
    /** @test */
    public function updated_bill_has_validations()
    {        
        $payment = factory(Bill::class)->create();

        $this->actingAs($this->admin)
                ->patch(route('payments.update', compact('payment')), [])
                ->assertStatus(302)
                ->assertSessionHasErrors([
                    'plan_user_id', 'payment_type_id', 'plan_user_id',
                    'date', 'amount'
                ])
                ->assertSessionDoesntHaveErrors(['detail']);
    }

    /** @test */
    public function it_updates_a_bill()
    {
        $payment = factory(Bill::class)->create();

        $new_values = [
            'plan_user_id'    => (int) $payment->plan_user_id,
            'payment_type_id' => PaymentType::EFECTIVO,
            'plan_user_id'    => (int) $payment->plan_user_id,
            'date'            => today()->format('d-m-Y'),
            'start_date'      => today()->format('d-m-Y'),
            'finish_date'     => today()->addMonth()->format('d-m-Y'),
            'detail'          => 'new detail',
            'amount'          => 59990
        ];

        $this->followingRedirects()
                ->actingAs($this->admin)
                ->patch(route('payments.update', compact('payment')), $new_values)
                ->assertStatus(200);

        $this->assertDatabaseHas('bills', [
            "id"              => (string) $payment->id,
            "payment_type_id" => (string) $new_values['payment_type_id'],
            "plan_user_id"    => (string) $new_values['plan_user_id'],
            "date"            => Carbon::parse($new_values['date'])->format('Y-m-d H:i:s'),
            "start_date"      => Carbon::parse($new_values['start_date'])->format('Y-m-d H:i:s'),
            "finish_date"     => Carbon::parse($new_values['finish_date'])->format('Y-m-d H:i:s'),
            "detail"          => $new_values['detail'],
            "amount"          => $new_values['amount']
        ]);
    }

    /** @test */
    public function it_destroy_a_bill()
    {
        $this->withoutExceptionHandling();
        
        $payment = factory(Bill::class)->create();
        $arrayedPayment = $payment->toArray();

        $this->followingRedirects()
                ->actingAs($this->admin)
                ->delete(route('payments.destroy', $arrayedPayment['id']))
                ->assertStatus(200);

        $this->assertEquals(Bill::find($payment->id), null);
    }
}
