<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class OtpFormRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'occupation' => 'required|string',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'email' => 'required|email'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->has('date_of_birth')) {
                return;
            }

            $dateOfBirth = Carbon::createFromFormat('Y-m-d', $this->input('date_of_birth'))->startOfDay();
            $age = $dateOfBirth->age;

            if ($age < 1 or $age > 75) {
                $validator->errors()->add(
                    'date_of_birth',
                    'Age must be between 1 and 75 years.'
                );
            }
        });
    }
}
