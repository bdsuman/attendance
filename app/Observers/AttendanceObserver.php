<?php

namespace App\Observers;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceObserver
{
    /**
     * Handle the Attendance "saved" event.
     */
    public function saved(Attendance $attendance): void
    {
        // Recompute calculated fields and persist quietly to avoid recursion.
        $attendance->computeCalculatedFields();
        $attendance->saveQuietly();
    }
}
