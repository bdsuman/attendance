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
        // Prefer raw stored seconds; fall back to accessor or helper when necessary
        $rawStart = $this->getRawOriginal('start_time') ?? $this->start_time;
        $rawEnd = $this->getRawOriginal('end_time') ?? $this->end_time;

        if (!is_numeric($rawStart)) {
            try {
                $startSeconds = convert_time_to_seconds($rawStart);
            } catch (\Throwable $e) {
                $startSeconds = 0;
            }
        } else {
            $startSeconds = (int) $rawStart;
        }

        if (!is_numeric($rawEnd)) {
            try {
                $endSeconds = convert_time_to_seconds($rawEnd);
            } catch (\Throwable $e) {
                $endSeconds = 0;
            }
        } else {
            $endSeconds = (int) $rawEnd;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            // Format times from stored seconds to HH:MM for API consumers
            'start_time' => seconds_to_hhmm($startSeconds),
            'end_time' => seconds_to_hhmm($endSeconds),
            'start_time_seconds' => $startSeconds,
            'end_time_seconds' => $endSeconds,
            'break_duration' => $this->break_duration,
            'late_grace_period' => $this->late_grace_period,
            'early_leave_grace_period' => $this->early_leave_grace_period,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
