<?php

namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\ReservationStatisticStage;

class Statistic extends Model
{
    /**
     * [$fillable description]
     * 
     * @var array
     */
    protected $fillable = ['statistic'];
  
    /**
      * [reservation_statistic_stages description]
      * 
      * @method reservation_statistic_stages
      * 
      * @return [model]
      */
    public function reservation_statistic_stages()
    {
        return $this->hasMany(ReservationStatisticStage::class);
    }
}
