<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Model;

class PlanIncomeSummary extends Model
{
    protected $fillable = ['plan_id', 'amount', 'month', 'year'];
    protected $table = 'plan_income_summaries';
}
