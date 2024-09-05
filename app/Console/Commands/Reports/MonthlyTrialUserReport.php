<?php

namespace App\Console\Commands\Reports;

use Carbon\Carbon;
use App\Models\Plans\Plan;
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
        $this->handleMonth(now()->startOfMonth()->subMonths(2));
    }

    public function handleMonth($startPreviousMonth)
    {
        $endOfPreviousMonth = $startPreviousMonth->copy()->endOfMonth();

        // planes de prueba acumulados (número de planes de prueba que se han entregado al mes)
        $allTrialPlans = $this->trialPlansAt($startPreviousMonth, $endOfPreviousMonth);
        // prueba clase consumido: todos los alumnos que tienen un plan de prueba donde la clase se haya consumido en el mes
        $trialClassesConsumed = $this->trialClassesConsumedAt($startPreviousMonth, $endOfPreviousMonth);
        
        // % prueba clase consumida: De todos los alumnos que tienen un plan de prueba en el mes, cuantos de estos han consumido al menos una clase
        $trialClassesConsumedPercentage = $trialClassesConsumed != 0 ? ($trialClassesConsumed / $allTrialPlans) * 100 : 0;
        
        $trialConvertion = $this->trialConvertionAt($startPreviousMonth, $endOfPreviousMonth);
        
        // % conversión: Cuantos de los alumnos con clases de prueba con al menos una clase consumida han comprado un plan normal despues.
        if ($trialClassesConsumed > 0) {
            $trialConvertionPercentage = ($trialConvertion / $trialClassesConsumed) * 100;
        } else {
            $trialConvertionPercentage = 0;
        }

        ReportModel::create([
            'year'                              => $startPreviousMonth->copy()->format('Y'),
            'month'                             => $startPreviousMonth->copy()->format('m'),
            'trial_plans'                       => $allTrialPlans,
            'trial_classes_consumed'            => $trialClassesConsumed,
            'trial_classes_consumed_percentage' => $trialClassesConsumedPercentage,
            'trial_convertion'                  => $trialConvertion,
            'trial_convertion_percentage'       => $trialConvertionPercentage
        ]);
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
            ->join('clases', 'clases.id', '=', 'reservations.clase_id')
            ->whereBetween('clases.date', [$start, $end])
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->where('reservations.reservation_status_id', ReservationStatus::CONSUMED)
            ->select('plan_user.id as id', 'plan_user.user_id', 'plans.id as plan_id')
            ->distinct('plan_user.user_id')
            ->count('plan_user.user_id');  
    }

    /**
     * Gets the number of users who converted to a normal plan after having a trial plan with at least one consumed class
     *
     * @param   Carbon  $start
     * @param   Carbon  $end
     *
     * @return  integer
     */
    public function trialConvertionAt(Carbon $start, Carbon $end)
    {
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', '!=', Plan::TRIAL)
            ->whereExists(function ($subQuery) use ($start, $end) {
                $subQuery->select(DB::raw(1))
                    ->from('plan_user as trialPlan')
                    ->join('reservations', 'reservations.plan_user_id', '=', 'trialPlan.id')
                    ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                    ->whereColumn('trialPlan.user_id', 'plan_user.user_id')
                    ->where('trialPlan.plan_id', Plan::TRIAL)
                    ->whereBetween('clases.date', [$start, $end])
                    ->where('trialPlan.plan_status_id', '!=', PlanStatus::CANCELED)
                    ->whereRaw('clases.date > DATE_SUB(plan_user.start_date, INTERVAL 14 DAY)')
                    ->where('reservations.reservation_status_id', ReservationStatus::CONSUMED)
                    ->whereNull('trialPlan.deleted_at');
            })
            ->whereNull('plan_user.deleted_at')
            ->distinct('plan_user.user_id')
            ->count('plan_user.user_id');
    }
}
