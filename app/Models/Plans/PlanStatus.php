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
	const FROZEN = 2;

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
            self::ACTIVE => 'ACTIVO',
            self::FROZEN => 'CONGELADO',
            self::PRE_PURCHASE => 'PRECOMPRA',
            self::FINISHED => 'COMPLETADO',
            self::CANCELED => 'CANCELADO',
        ];
    }

    /**
     * Get ids of all reactivable Plans
     *
     * @return  array
     */
    public function reactivablePlans()
    {
        return [ self::FINISHED, self::CANCELED ];
    }

    /**
     * Return all ReservationStatusColors
     *
     * @return  array
     */
    public function listPlanStatusColors()
    {
        return [
            self::ACTIVE => 'success',
            self::FROZEN  => 'info',
            self::PRE_PURCHASE => 'info',
            self::FINISHED => 'primary',
            self::CANCELED => 'danger',
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
