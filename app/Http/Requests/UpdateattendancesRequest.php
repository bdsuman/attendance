<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
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
            'entry_date'   => 'sometimes|required|date',
            'employee_id'  => 'sometimes|required|exists:employees,id',
            'shift_id'     => 'sometimes|required|exists:shifts,id',
            'check_in'     => 'sometimes|required|date',
            'check_out'    => 'nullable|date|after_or_equal:check_in',
            'break_in'     => 'nullable|date|after_or_equal:check_in',
            'break_out'    => 'nullable|date|after_or_equal:break_in',
        ];
    }
}
