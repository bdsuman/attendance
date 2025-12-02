<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
    $employee = $this->route('employee'); // route parameter name singular 'employee'

    return [
        'first_name' => 'sometimes|required|string|max:255',
        'last_name' => 'sometimes|required|string|max:255',
        'employee_no' => [
            'sometimes',
            'required',
            'string',
            'max:255',
            Rule::unique('employees', 'employee_no')->ignore($employee->id),
        ],
        'phone' => [
            'sometimes',
            'required',
            'string',
            'max:20',
            Rule::unique('employees', 'phone')->ignore($employee->id),
        ],
        'email' => [
            'sometimes',
            'required',
            'email',
            'max:255',
            Rule::unique('employees', 'email')->ignore($employee->id),
        ],
        'designation' => 'sometimes|required|string|max:255',
        'department' => 'sometimes|required|string|max:255',
    ];
}
}
