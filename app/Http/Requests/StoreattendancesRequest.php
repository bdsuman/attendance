<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
           'entry_date'   => 'required|date',
            'employee_id'  => 'required|exists:employees,id',
            'shift_id'     => 'required|exists:shifts,id',
            'check_in'     => 'required|date',
            'check_out'    => 'nullable|date|after_or_equal:check_in',
            'break_in'     => 'nullable|date|after_or_equal:check_in',
            'break_out'    => 'nullable|date|after_or_equal:break_in',
        ];
    }
}
