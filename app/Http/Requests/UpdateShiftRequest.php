<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
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
             'name'                       => 'sometimes|required|string|max:255',
            'start_time'                 => [
                'sometimes',
                'required',
                'regex:/^(\d+|\d{1,2}:\d{2}(:\d{2})?)$/',
            ],
            'end_time'                   => [
                'sometimes',
                'required',
                'regex:/^(\d+|\d{1,2}:\d{2}(:\d{2})?)$/',
            ],
            'break_duration'             => 'sometimes|required|integer|min:0',
            'late_grace_period'          => 'sometimes|required|integer|min:0',
            'early_leave_grace_period'   => 'sometimes|required|integer|min:0',
        ];
    }
}
