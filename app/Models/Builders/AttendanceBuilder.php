<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class AttendanceBuilder extends EloquentBuilder
{

    /**
     * Filter by employee id.
     */
    public function filterEmployee($employeeId)
    {
        return $this->when($employeeId, function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        });
    }

    /**
     * Filter by entry date.
     */
    public function filterDate($date)
    {
        return $this->when($date, function ($q) use ($date) {
            $q->whereDate('entry_date', $date);
        });
    }

    /**
     * Filter by calculated status.
     */
    public function filterStatus($status)
    {
        return $this->when($status, function ($q) use ($status) {
            $q->where('calculated_status', $status);
        });
    }
}
