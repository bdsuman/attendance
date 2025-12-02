<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Builders\AttendanceBuilder;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $fillable = [
            'entry_date',
            'employee_id',
            'shift_id',
            'check_in',
            'check_out',
            'break_in',
            'break_out',
            'worked_seconds',
            'worked_hours',
            'calculated_status',
            'late_minutes',
            'early_leave_minutes',
            'is_active'
        ];

    protected $casts = [
            'entry_date'    => 'date',
            'check_in'         => 'datetime',
            'check_out'         => 'datetime',
            'break_in'   => 'datetime',
            'break_out'     => 'datetime',
            'worked_seconds' => 'integer',
            'worked_hours' => 'float',
            'calculated_status' => 'string',
            'late_minutes' => 'integer',
            'early_leave_minutes' => 'integer',
            'is_active'     => 'boolean'
        ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function shift(){
        return $this->belongsTo(Shift::class);
    }

    /**
     * Compute calculated fields on this model instance (in-memory) but do not save.
     * Other code can call `save()` or `saveQuietly()` after.
     */
    public function computeCalculatedFields(): void
    {
        $workedSeconds = 0;
        if ($this->check_in && $this->check_out) {
            $total = $this->check_out->diffInSeconds($this->check_in);
            $breakSeconds = 0;
            if ($this->break_in && $this->break_out) {
                $breakSeconds = $this->break_out->diffInSeconds($this->break_in);
            }
            $workedSeconds = max(0, $total - $breakSeconds);
        }

        $this->worked_seconds = $workedSeconds;
        $this->worked_hours = round($workedSeconds / 3600, 2);

        $lateMinutes = 0;
        $earlyLeaveMinutes = 0;
        $status = 'incomplete';

        $shift = $this->shift ?: \App\Models\Shift::find($this->shift_id);
        if ($shift) {
            $shiftStartSec = $shift->getRawOriginal('start_time') ?? 0;
            $shiftEndSec = $shift->getRawOriginal('end_time') ?? 0;
            $lateGraceSec = $shift->getRawOriginal('late_grace_period') ?? 0;
            $earlyGraceSec = $shift->getRawOriginal('early_leave_grace_period') ?? 0;

            $date = $this->entry_date ? $this->entry_date->toDateString() : Carbon::now()->toDateString();
            $shiftStartTs = Carbon::parse($date)->startOfDay()->addSeconds($shiftStartSec);
            $shiftEndTs = Carbon::parse($date)->startOfDay()->addSeconds($shiftEndSec);

            if ($this->check_in) {
                $graceStart = $shiftStartTs->copy()->addSeconds($lateGraceSec);
                    if ($this->check_in->greaterThan($graceStart)) {
                        $lateMinutes = (int) max(0, $this->check_in->diffInMinutes($graceStart));
                }
            }

            if ($this->check_out) {
                $graceEnd = $shiftEndTs->copy()->subSeconds($earlyGraceSec);
                    if ($this->check_out->lessThan($graceEnd)) {
                        $earlyLeaveMinutes = (int) max(0, $graceEnd->diffInMinutes($this->check_out));
                }
            }

            if ($lateMinutes > 0) {
                $status = 'late';
            } elseif ($earlyLeaveMinutes > 0) {
                $status = 'early_leave';
            } elseif ($this->check_in && $this->check_out) {
                $status = 'on_time';
            } else {
                $status = 'incomplete';
            }
        }

        $this->late_minutes = $lateMinutes;
        $this->early_leave_minutes = $earlyLeaveMinutes;
        $this->calculated_status = $status;
    }

    

    /**
     * Return a custom Eloquent builder for this model.
     * This enables chaining custom builder methods like `filtered()`.
     */
    public function newEloquentBuilder($query)
    {
        return new AttendanceBuilder($query);
    }
}
