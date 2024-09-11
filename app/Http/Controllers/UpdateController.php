<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateController extends Controller
{
    public function updatePlanUserDate()
    {
        $planUsers = \App\Models\Plans\PlanUser::where('finish_date', '>=', now()->format('Y-m-d'))->get();

        \App\Models\Plans\PlanUser::withoutEvents(function () use ($planUsers) {
            foreach ($planUsers as $planUser) {
                $planUser->finish_date = Carbon::parse($planUser->finish_date)->format('Y-m-d') . ' 23:59:59';
                $planUser->save();
            }
        });
    }

    public function updateClaseDate()
    {
        $clases = \App\Models\Clases\Clase::where('date', '>=', now()->format('Y-m-d'))->get();

        \App\Models\Clases\Clase::withoutEvents(function () use ($clases) {
            foreach ($clases as $clase) {
                $clase->date = Carbon::parse($clase->date)->format('Y-m-d') . Carbon::parse($clase->start_at)->format(' H:i:s');
                $clase->save();
            }
        });
    }

    public function updatePlanUserFlows()
    {
        $planUserFlows = \App\Models\Plans\PlanUserFlow::where('finish_date', '>=', now()->format('Y-m-d'))->get();

        \App\Models\Plans\PlanUserFlow::withoutEvents(function () use ($planUserFlows) {
            foreach ($planUserFlows as $planUserFlow) {
                $planUserFlow->finish_date = Carbon::parse($planUserFlow->finish_date)->format('Y-m-d') . ' 23:59:59';
                $planUserFlow->save();
            }
        });
    }

    public function testPush()
    {
        $pushClass = new \App\Jobs\SendPushNotification(
            'fBqwD-OtSj-fvGcJVgk5k4:APA91bGv9v0oDAPqym-Wp7m2hLaG7SvRDH5bf-iAXiKwfGhPmUW4RrQL9y-LDg0CW0A2KMUMj2m9x_Z2w_zwoAYnwe_61jqyseg7sTyMd8uPmOgUbrk9rsbyR3jiV_UQq1sltC-2nXpk',
            'Test title',
            'Test body',
            resolve('App\Services\PushNotificationService')
        );

        $pushClass->handle();

        return 'ok';
    }
}
