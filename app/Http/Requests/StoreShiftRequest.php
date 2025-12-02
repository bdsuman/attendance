<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'name'                     => 'required|string|max:255',
        'start_time'               => [
            'required',
            'regex:/^\d{1,2}:\d{2}(:\d{2})?$/', // matches HH:MM or HH:MM:SS
        ],
        'end_time'                 => [
            'required',
            'regex:/^\d{1,2}:\d{2}(:\d{2})?$/', // matches HH:MM or HH:MM:SS
        ],
        'break_duration'           => 'required|integer|min:0', // in minutes
        'late_grace_period'        => 'required|integer|min:0', // in minutes
        'early_leave_grace_period' => 'required|integer|min:0', // in minutes
    ];
}
}
