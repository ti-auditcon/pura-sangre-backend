<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class MonthlyTrialUserReport extends Model
{
    /*
     * year                            int    The year of the report
     * month                           int    The month of the report
     * plans_sold                      int    Number of plans sold during the month
     * trial_users                     int    Number of trial users during the month
     * trial_classes_consumed          int    Number of trial classes consumed
     * trial_classes_taken_percentage  float  Percentage of trial classes taken
     * trial_conversion                int    Number of trial users who converted to full membership
     * trial_conversion_percentage     float  Percentage of trial users who converted to full membership
     * trial_retention_percentage      float  Percentage of trial users retained after conversion
     * inactive_users                  int    Number of inactive users during the month
     */ 
    protected $fillable = [
        'year', 'month', 'plans_sold', 'trial_users', 'trial_classes_consumed',
        'trial_classes_taken_percentage', 'trial_conversion', 'trial_conversion_percentage',
        'trial_retention_percentage', 'inactive_users'
    ];
}
