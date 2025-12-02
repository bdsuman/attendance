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
            // Accept either `employee_id` or `emp_id` in request body
            'employee_id' => 'required_without:emp_id|nullable|integer|exists:employees,id',
            'emp_id' => 'required_without:employee_id|nullable|integer|exists:employees,id',

            'shift_id' => 'required|integer|exists:shifts,id',

            // status indicates which timestamp to set/update
            'status' => 'required|string|in:check_in,check_out,break_in,break_out',

            // optional date for the attendance entry (defaults to today)
            'entry_date' => 'sometimes|date',
        ];
    }
}
