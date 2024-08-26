<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class MonthlyStudentReport extends Model
{
    /**
     * year                        int    The year of the report
     * month                       int    The month of the report
     * active_students_start       int    Number of active students at the start of the month
     * active_students_end         int    Number of active students at the end of the month
     * dropouts                    int    Number of students who dropped out during the month
     * new_students                int    Number of new students who joined during the month
     * new_students_percentage     float  Percentage of new students who joined
     * dropout_percentage          float  Percentage of students who dropped out
     * students_returned            int   Number of students who returned or reactivated
     * students_returned_rate        float  Students who returned or reactivated
     * month_difference   int    Difference in dropouts compared to the previous month
     * growth_rate                 float  Growth rate of the student population
     * retention_rate              float  Retention rate of the students
     * churn_rate                  float  Churn Rate represents the percentage of users who have left the service (or stopped being active) within a specific time frame relative to the total number of users at the beginning of that period
     */
    protected $fillable = [
        'year',
        'month', 
        'active_students_start', 
        'active_students_end', 
        'dropouts',
        'dropout_percentage', 
        'new_students', 
        'new_students_percentage', 
        'students_returned',
        'students_returned_rate',
        'month_difference', 
        'growth_rate', 
        'retention_rate', 
        'churn_rate'
    ];
}
