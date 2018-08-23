<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

/**
 * [Plan description]
 */
class Plan extends Model
{
  protected $fillable = ['plan', 'class_numbers'];

  /**
   * [installments description]
   * @method installments
   * @return [type]       [description]
   */
  public function plan_users()
  {
    return $this->hasMany(PlanUser::class);
  }

}
