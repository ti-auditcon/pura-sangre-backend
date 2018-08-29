<?php

namespace App\Models\Plans;

use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\Discount;
use App\Models\Bills\Installment;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

/** [PlanUser description] */
class PlanUser extends Pivot
{
  use SoftDeletes;

  protected $table = 'plan_user';
  protected $dates = ['deleted_at'];
  protected $fillable = ['discount_id', 'plan_id', 'start_time', 'finish_time'];

  /**
  * [discount description]
  * @method discount
  * @return [model]   [description]
  */
  public function discount()
  {
    return $this->hasOne(Discount::class);
  }

  /**
   * [installments description]
   * @method installments
   * @return [model]       [description]
   */
  public function installments()
  {
      return $this->hasMany(Installment::class);
  }

  /**
   * [plan description]
   * @method plan
   * @return [model] [description]
   */
  public function plan()
  {
      return $this->belongsTo(Plan::class);
  }

  /**
   * [user description]
   * @method user
   * @return [model] [description]
   */
  public function user()
  {
      return $this->belongsTo(User::class);
  }
}
