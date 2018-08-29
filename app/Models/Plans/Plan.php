<?php

namespace App\Models\Plans;

use App\Models\Users\User;
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

  /**
   * [users description]
   * @return [type] [description]
   */
  public function users()
  {
    return $this->belongsToMany(User::class)->using(PlanUser::class);
  }

}
