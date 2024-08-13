<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class MonthlyTrialUserReport extends Model
{
    /**
     * year                               int    The year of the report
     * month                              int    The month of the report
     * trial_plans                        int    Number of trial plans sold during the month
     * trial_classes_consumed
     * trial_classes_consumed_percentage  float  Percentage of trial classes taken
     * trial_convertion 
     * trial_convertion_percentage
     * new_users_with_trial_plan
     */ 
    protected $fillable = [
        'year',
        'month',
        'trial_plans',
        'trial_classes_consumed',
        'trial_classes_consumed_percentage',
        'trial_convertion',
        'trial_convertion_percentage',
        'new_users_with_trial_plan',
    ];
}
