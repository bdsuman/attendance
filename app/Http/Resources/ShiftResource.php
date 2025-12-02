<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'start_time' => seconds_to_hhmm($this->start_time),
            'end_time' => seconds_to_hhmm($this->end_time),
            'break_duration' => seconds_to_mm($this->break_duration),
            'late_grace_period' => seconds_to_mm($this->late_grace_period),
            'early_leave_grace_period' => seconds_to_mm($this->early_leave_grace_period),
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
