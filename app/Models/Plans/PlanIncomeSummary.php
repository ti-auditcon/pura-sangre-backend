<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PlanIncomeSummary extends Model
{
	/**
	 * [$fillable description]
	 * 
	 * @var [type]
	 */
    protected $fillable = ['plan_id', 'amount', 'month', 'year', 'quantity'];
    
    /**
     * 
     * @var string
     */
    protected $table = 'plan_income_summaries';

    /**
     * [plan description]
     * 
     * @return [type] [description]
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
