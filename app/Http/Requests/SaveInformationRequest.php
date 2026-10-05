<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveInformationRequest extends FormRequest
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
            'prefix' => 'required|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nationality' => 'required|string',
            'identity_type' => 'required|string',
            'identity_number' => 'required|string|max:13',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string|max:500',
            'province' => 'required|string|max:255',
            'province_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'district_name' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'subdistrict_name' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'beneficiary' => 'nullable|string|max:255',
            'personal_data_collection' => 'accepted',
            'sensitive_personal_data' => 'accepted',
            'marketing_consent' => 'nullable|boolean',
        ];
    }
}
