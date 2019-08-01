<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Model;

class PlanStatus extends Model
{
	/** ID status Plan */
	const ACTIVO = 1; 
	
	/** ID status Plan */
	const INACTIVO = 2; 
	
	/** ID status Plan */
	const PRECOMPRA = 3; 
	
	/** ID status Plan */
	const COMPLETADO = 4; 
	
	/** ID status Plan */
	const CANCELADO = 5; 

	/**
	 * Define table name
	 * @var string
	 */
	protected $table = 'plan_status';
}
