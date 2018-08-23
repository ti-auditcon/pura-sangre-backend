<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
  protected $fillable = ['discount', 'percent'];

  /**
   * [plan_users description]
   * @method plan_users
   * @return [model]     [description]
   */
  public function plan_users()
  {
    return $this->hasMany(PlanUser::class);
  }
}
