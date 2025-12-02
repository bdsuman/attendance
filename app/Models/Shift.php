<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftFactory> */
    use HasFactory;

      protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'break_duration',
        'late_grace_period',
        'early_leave_grace_period',
        'is_active'
    ];

    protected $casts = [
        'name'                      => 'string',
        // 'start_time'                => 'integer',
        // 'end_time'                  => 'integer',
        'break_duration'            => 'integer',
        'late_grace_period'         => 'integer',
        'early_leave_grace_period'  => 'integer',
        'is_active'                 => 'boolean',
    ];

    public function Attendance(){
            return $this->hasMany(Attendance::class);
    }

     protected static function boot()
{
    parent::boot();

    static::saving(function ($shift) {
        // Convert start_time and end_time from HH:MM or HH:MM:SS to seconds only when needed
        foreach (['start_time', 'end_time'] as $field) {
            $val = $shift->$field;

            // If the value is a string containing colon, convert using helper
            if (is_string($val) && strpos($val, ':') !== false) {
                $shift->$field = convert_time_to_seconds($val);
                continue;
            }

            // If it's numeric, assume it's already in seconds and ensure integer
            if (is_numeric($val)) {
                $shift->$field = (int) $val;
                continue;
            }

            // Fallback to 0
            $shift->$field = 0;
        }

        // Convert minute-based fields (from request as minutes) to seconds.
        // If value seems already large (likely seconds), leave as-is.
        $minuteFields = ['break_duration', 'late_grace_period', 'early_leave_grace_period'];
        foreach ($minuteFields as $field) {
            if (! isset($shift->$field) || $shift->$field === null) {
                $shift->$field = 0;
                continue;
            }

            $val = $shift->$field;

            if (is_numeric($val)) {
                $intVal = (int) $val;
                // If value > 1000 assume it is already seconds (e.g., 3600), otherwise treat as minutes
                if ($intVal > 1000) {
                    $shift->$field = $intVal;
                } else {
                    $shift->$field = $intVal * 60;
                }
            } else {
                $shift->$field = 0;
            }
        }
    });
}

    /**
     * Convert HH:MM or HH:MM:SS to seconds
     */
// The model now relies on the global helper `convert_time_to_seconds()`
// for parsing HH:MM or HH:MM:SS formats when needed.


    // Time formatting is handled in the `ShiftResource` (seconds -> HH:MM)


// // Convert break_duration (seconds) to minutes
// public function getBreakDurationAttribute($value)
// {
//     return $value !== null ? (int) ($value / 60) : null;
// }

// // Convert late_grace_period (seconds) to minutes
// public function getLateGracePeriodAttribute($value)
// {
//     return $value !== null ? (int) ($value / 60) : null;
// }

// // Convert early_leave_grace_period (seconds) to minutes
// public function getEarlyLeaveGracePeriodAttribute($value)
// {
//     return $value !== null ? (int) ($value / 60) : null;
// }

}
