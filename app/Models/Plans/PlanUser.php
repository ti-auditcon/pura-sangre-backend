<?php

namespace App\Models\Plans;

use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\Discount;
use App\Models\Bills\Installment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanUser extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['discount_id', 'plan_id', 'start_time', 'finish_time'];

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
   * [discount description]
   * @method discount
   * @return [model]   [description]
   */
  public function discount()
  {
      return $this->hasOne(Discount::class);
  }

  /**
   * [plan description]
   * @method plan
   * @return [model] [description]
   */
  public function plan()
  {
      return $this->hasOne(Plan::class);
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
