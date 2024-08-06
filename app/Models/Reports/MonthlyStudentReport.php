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
     * dropout_percentage          float  Percentage of students who dropped out
     * new_students_percentage     float  Percentage of new students who joined
     * turnaround                  int    Number of students who returned or reactivated
     * previous_month_difference   int    Difference in dropouts compared to the previous month
     * growth_rate                 float  Growth rate of the student population
     * retention_rate              float  Retention rate of the students
     * rotation                    float  Rotation rate of the students
     */
    protected $fillable = [
        'year', 'month', 'active_students_start', 'active_students_end', 'dropouts',
        'dropout_percentage', 'new_students', 'new_students_percentage', 'turnaround',
        'previous_month_difference', 'growth_rate', 'retention_rate', 'rotation'
    ];
}
