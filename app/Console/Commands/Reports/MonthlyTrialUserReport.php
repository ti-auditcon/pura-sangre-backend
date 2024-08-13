<?php

namespace App\Console\Commands\Reports;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Clases\ReservationStatus;
use App\Models\Reports\MonthlyTrialUserReport as ReportModel;

class MonthlyTrialUserReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:reports:monthly-trial-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close data for a month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = Carbon::parse('2018-12-01');
        $end = Carbon::parse('2024-07-31');

        while ($start->lte($end)) {
            $this->handleMonth($start->copy()->startOfMonth());

            $start->addMonth();
        }
    }

    public function handleMonth($startPreviousMonth)
    {
        $endOfPreviousMonth = $startPreviousMonth->copy()->endOfMonth();

        // planes de prueba acumulados (número de planes de prueba que se han entregado al mes)
        $trialPlans = $this->trialPlansAt($startPreviousMonth, $endOfPreviousMonth);
        
        // prueba clase consumido: todos los alumnos que tienen un plan de prueba donde la clase se haya consumido en el mes
        $trialClassesConsumed = $this->trialClassesConsumedAt($startPreviousMonth, $endOfPreviousMonth);

        // % prueba clase consumida: De todos los alumnos que tienen un plan de prueba en el mes, cuantos de estos han consumido al menos una clase
        $trialClassesTakenPercentage = $this->trialClassesConsumedPercentageAt($startPreviousMonth, $endOfPreviousMonth, $trialClassesConsumed);

        // numero de convertidos: todos los alumnos que han contradado un plan normal despues de tener un plan de prueba con al menos una clase consumida
        $trialConvertion = $this->trialConvertionAt($startPreviousMonth, $endOfPreviousMonth, $trialClassesConsumed);

        // % conversión: Cuantos de los alumnos con clases de prueba con al menos una clase consumida han comprado un plan normal despues.
        $trialConvertionPercentage = $trialConvertion != 0 ? ($trialConvertion / $trialClassesConsumed) * 100 : 0;

        // % alumnos nuevos que tuvieron un plan de prueba: Cuantos de los alumnos nuevos alumnos tuvieron un plan de prueba antes
        $newStudentsPercentage = $newStudents != 0 ? ($newStudents / $activeUserStart) * 100 : 0;
    }

    public function trialPlansAt($start, $end)
    {
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('plan_user.id as id', 'plan_user.user_id', 'plans.id as plan_id')
            ->distinct('plan_user.id')
            ->count('plan_user.id');
    }

    public function trialClassesConsumedAt($start, $end)
    {
        // todos los alumnos que tienen un plan de prueba donde la clase se haya consumido en el mes
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->join('reservations', 'reservations.plan_user_id', '=', 'plan_user.id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereNot('plan_user.plan_status_id', PlanStatus::CANCELED)
            ->where('plans.id', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->where('reservations.reservation_status_id', ReservationStatus::CONSUMED)
            ->select('plan_user.id as id', 'plan_user.user_id', 'plans.id as plan_id')
            ->distinct('plan_user.id')
            ->count('plan_user.id');
    }

    public function trialClassesConsumedPercentageAt($start, $end, $trialClassesConsumed)
    {
        $total = PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereNot('plan_user.plan_status_id', PlanStatus::CANCELED)
            ->where('plans.id', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('plan_user.id as id', 'plan_user.user_id', 'plans.id as plan_id')
            ->distinct('plan_user.id')
            ->count('plan_user.id');

        return 100 * $trialClassesConsumed / $total;
    }


    // todos los alumnos que han contradado un plan normal despues de tener un plan de prueba con al menos una clase consumida
    public function trialConvertionAt($start, $end, $trialClassesConsumed)
    {
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereNot('plan_user.plan_status_id', PlanStatus::CANCELED)
            ->where('plans.id', '!=', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('plan_user as previousPlan')
                    ->join('reservations', 'reservations.plan_user_id', '=', 'previousPlan.id')
                    ->whereColumn('previousPlan.plan_status_id', '!=', PlanStatus::CANCELED)
                    ->whereColumn('previousPlan.user_id', 'plan_user.user_id')
                    ->whereRaw('previousPlan.finish_date < plan_user.start_date')
                    ->where('previousPlan.plan_id', Plan::TRIAL)
                    ->where('reservations.reservation_status_id', ReservationStatus::CONSUMED)
                    ->whereNull('previousPlan.deleted_at');
            })
            ->distinct('plan_user.id')
            ->count('plan_user.id', 'id');
    }
}
