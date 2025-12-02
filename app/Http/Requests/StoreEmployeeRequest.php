<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'employee_no'   => 'required|string|max:255|unique:employees,employee_no',
            'phone'         => 'required|string|max:20|unique:employees,phone',
            'email'         => 'required|email|max:255|unique:employees,email',
            'designation'   => 'required|string|max:255',
            'department'     => 'required|string|max:255',
        ];
    }
}
