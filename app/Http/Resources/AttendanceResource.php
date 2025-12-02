<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee' => $this->whenLoaded('employee'),
            'shift_id' => $this->shift_id,
            'shift' => $this->whenLoaded('shift') ? new ShiftResource($this->whenLoaded('shift')) : null,
            'entry_date' => $this->entry_date ? (string) $this->entry_date : null,
            'check_in' => $this->check_in ? $this->check_in->toDateTimeString() : null,
            'check_in_time' => $this->check_in ? $this->check_in->format('H:i') : null,
            'break_in' => $this->break_in ? $this->break_in->toDateTimeString() : null,
            'break_in_time' => $this->break_in ? $this->break_in->format('H:i') : null,
            'break_out' => $this->break_out ? $this->break_out->toDateTimeString() : null,
            'break_out_time' => $this->break_out ? $this->break_out->format('H:i') : null,
            'check_out' => $this->check_out ? $this->check_out->toDateTimeString() : null,
            'check_out_time' => $this->check_out ? $this->check_out->format('H:i') : null,

            // persisted computed fields
            'worked_seconds' => $this->worked_seconds ?? null,
            'worked_hours' => $this->worked_hours ?? null,
            'worked_hhmm' => isset($this->worked_seconds) ? seconds_to_hhmm($this->worked_seconds) : null,
            'calculated_status' => $this->calculated_status ?? null,
            'late_minutes' => $this->late_minutes ?? null,
            'late_hhmm' => isset($this->late_minutes) ? seconds_to_hhmm($this->late_minutes * 60) : null,
            'early_leave_minutes' => $this->early_leave_minutes ?? null,
            'early_leave_hhmm' => isset($this->early_leave_minutes) ? seconds_to_hhmm($this->early_leave_minutes * 60) : null,

            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
