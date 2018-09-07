<?php

namespace App\Models\Plans;

use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanPeriod;
use Illuminate\Database\Eloquent\Model;

/**
 * [Plan description]
 */
class Plan extends Model
{
  protected $fillable = ['plan', 'plan_period_id', 'class_numbers', 'amount'];

  /**
   * [plan_period description]
   * @return [type] [description]
   */
  public function plan_period()
  {
      return $this->belongsTo(PlanPeriod::class);
  }

  /**
   * [installments description]
   * @method installments
   * @return [type]       [description]
   */
  public function plan_users()
  {
    return $this->hasMany(PlanUser::class);
  }

  /**
   * [users description]
   * @return [type] [description]
   */
  public function users()
  {
    return $this->belongsToMany(User::class)->using(PlanUser::class);
  }

}
