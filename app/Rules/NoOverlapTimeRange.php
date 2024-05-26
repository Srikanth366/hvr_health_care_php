<?php 
// app/Rules/NoOverlapTimeRange.php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\WorkingHour;

class NoOverlapTimeRange implements Rule
{
    protected $doctor_id;
    protected $day_of_week;
    protected $start_time;
    protected $end_time;
    protected $ignore_id;

    public function __construct($doctor_id, $day_of_week, $start_time, $end_time, $ignore_id = null)
    {
        $this->doctor_id = $doctor_id;
        $this->day_of_week = $day_of_week;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->ignore_id = $ignore_id;
    }

    public function passes($attribute, $value)
    {
        $query = WorkingHour::where('doctor_id', $this->doctor_id)
                            ->where('day_of_week', $this->day_of_week)
                            ->where(function($q) {
                                $q->whereBetween('start_time', [$this->start_time, $this->end_time])
                                  ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                                  ->orWhere(function($q) {
                                      $q->where('start_time', '<=', $this->start_time)
                                        ->where('end_time', '>=', $this->end_time);
                                  });
                            });

        if ($this->ignore_id) {
            $query->where('id', '!=', $this->ignore_id);
        }

        return $query->doesntExist();
    }

    public function message()
    {
        return 'The specified time range overlaps with an existing working hour.';
    }
}
