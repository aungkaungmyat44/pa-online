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

    public function messages(): array
    {
        return [
            'occupation.required' => __('content.otp.validation.occupation_required'),
            'date_of_birth.required' => __('content.otp.validation.date_of_birth_required'),
            'date_of_birth.date_format' => __('content.otp.validation.date_of_birth_format'),
            'email.required' => __('content.otp.validation.email_required'),
            'email.email' => __('content.otp.validation.email_format'),
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

            if ($age < 16  or $age > 60) {
                $validator->errors()->add(
                    'date_of_birth',
                    __('content.otp.validation.date_of_birth_age')
                );
            }
        });
    }
}
