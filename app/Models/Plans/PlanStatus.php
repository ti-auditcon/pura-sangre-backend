<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Model;

class PlanStatus extends Model
{
	/** ID status Plan */
	const ACTIVO = 1;

	const ACTIVE = 1;

	/** ID status Plan */
	const CONGELADO = 2;
	const FREEZED = 2;

	/** ID status Plan */
	const PRECOMPRA = 3;
	const PRE_PURCHASE = 3;

	/** ID status Plan */
	const COMPLETADO = 4;
	const COMPLETED = 4;
	const FINISHED = 4;

	/** ID status Plan */
	const CANCELADO = 5;
    const CANCELED = 5;

    /**
     * Name of the table in the database
     *
     * @var  string
     */
    protected $table = 'plan_status';

    /**
     * list All Plan Status
     *
     * @return  array
     */
    public function listAllPlanStatus()
    {
        return [
            self::ACTIVO     =>  'ACTIVO',
            self::CONGELADO   =>  'CONGELADO',
            self::PRECOMPRA  =>  'PRECOMPRA',
            self::COMPLETADO =>  'COMPLETADO',
            self::CANCELADO  =>  'CANCELADO',
        ];
    }

    /**
     * Get ids of all reactivable Plans
     *
     * @return  array
     */
    public function reactivablePlans()
    {
        return [ self::COMPLETADO, self::CANCELADO ];
    }

    /**
     * Return all ReservationStatusColors
     *
     * @return  array
     */
    public function listPlanStatusColors()
    {
        return [
            self::ACTIVO     =>  'success',
            self::CONGELADO   =>  'info',
            self::PRECOMPRA  =>  'info',
            self::COMPLETADO =>  'primary',
            self::CANCELADO  =>  'danger',
        ];
    }

    /**
     * Return a Plan Status by an specific Id
     *
     * @param   integer   Id for a status
     *
     * @return  string    A Plan Status
     */
    public function getPlanStatus($planStatusId)
    {
        $plan_statuses = $this->listAllPlanStatus();

        return $plan_statuses[$planStatusId] ?? 'SIN ESTADO';
    }

    /**
     * Return a Plan Status by an specific Id
     *
     * @param   integer   Id for a status
     *
     * @return  string    A Plan Status
     */
    public function getPlanStatusColor($planStatusId)
    {
        $plan_statuses_colors = $this->listPlanStatusColors();

        return $plan_statuses_colors[$planStatusId] ?? 'SIN ESTADO';
    }
}
