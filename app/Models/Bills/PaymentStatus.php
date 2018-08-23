<?php

namespace App\Models\Bills;

use App\Models\Bills\Installment;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
  protected $fillable = ['payment_status'];
  
  /**
   * [installment description]
   * @method installment
   * @return [model]      [description]
   */
  public function installments()
  {
    return $this->hasMany(Installment::class);
  }
}
