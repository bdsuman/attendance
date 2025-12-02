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
            'break_in' => $this->break_in ? $this->break_in->toDateTimeString() : null,
            'break_out' => $this->break_out ? $this->break_out->toDateTimeString() : null,
            'check_out' => $this->check_out ? $this->check_out->toDateTimeString() : null,

            // persisted computed fields
            'worked_seconds' => $this->worked_seconds ?? null,
            'worked_hours' => $this->worked_hours ?? null,
            'calculated_status' => $this->calculated_status ?? null,
            'late_minutes' => $this->late_minutes ?? null,
            'early_leave_minutes' => $this->early_leave_minutes ?? null,

            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
