<?php

namespace App\Models\Plans;

use App\Models\Users\User;
use App\Models\Clases\Block;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanPeriod;
use Illuminate\Database\Eloquent\Model;

/**
 * [Plan model]
 */
class Plan extends Model
{
  protected $fillable = ['plan', 'plan_period_id', 'class_numbers', 'amount'];

  /**
   * [blocks relation to this model]
   * @return [model] [return model]
   */
  public function blocks()
  {
      return $this->hasMany(Block::class);
  }

  /**
   * [plan_period relation to this model]
   * @return [model] [return plan_period model]
   */
  public function plan_period()
  {
      return $this->belongsTo(PlanPeriod::class);
  }

  /**
   * [plan_users relation to this model]
   * @return [model] [return plan_users model]
   */
  public function user_plans()
  {
    return $this->hasMany(PlanUser::class);
  }

  /**
   * [users relation to this model]
   * @return [model] [return users model]
   */
  public function users()
  {
    return $this->belongsToMany(User::class)->using(PlanUser::class);
  }

}
