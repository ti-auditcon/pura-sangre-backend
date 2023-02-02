<?php

namespace Tests\Traits;

use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanPeriod;
use App\Models\Plans\PlanStatus;

trait PlanFactoryTrait
{
    public function fakeActivePlanUser($extra_data = [])
    {
        $planId = factory(Plan::class)->create()->id;

        return PlanUser::withoutEvents(function () use ($planId, $extra_data) {
            return factory(PlanUser::class)->create(array_merge([
                'start_date' => now(),
                'finish_date' => now()->addDays(30),
                'plan_id' => $planId,
                'user_id' => $this->fakeStudent()->id,
                'plan_status_id' => PlanStatus::ACTIVE,
            ], $extra_data));
        });
    }

    /**
     * [fakePlan description]
     *
     * @param   [type]  $extra_data  [$extra_data description]
     *
     * @return  []                   [return description]
     */
    public function fakePlan($extra_data = [])
    {
        return Plan::withoutEvents(function () use ($extra_data) {
            return factory(Plan::class)->create(array_merge([
                'plan'           => 'Fake plan',
                'plan_period_id' => PlanPeriod::MONTHLY,
                'amount'         => 49990,
                'description'    => 'Description of the fake plan',
                'contractable'   => true,
                'class_numbers'  => 1,
                'daily_clases'   => 1,
                'custom'         => false,
                'image_path'     => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
                'contractable'   => true,
            ], $extra_data));
        });
    }
}
