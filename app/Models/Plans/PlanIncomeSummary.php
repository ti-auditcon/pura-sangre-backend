<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PlanIncomeSummary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['plan_id', 'amount', 'month', 'year', 'quantity'];

    /**
     * Name of the table in the database
     *
     * @var  string
     */
    protected $table = 'plan_income_summaries';

    /**
     * [plan description]
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
