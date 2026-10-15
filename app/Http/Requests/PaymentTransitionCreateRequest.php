<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentTransitionCreateRequest extends FormRequest
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
            'reference_order' => 'required|string',
            'charge_id' => 'required|string',
            'status' => 'required|in:success,fail',
            'transaction_state' => 'required|string',
            'amount' => 'required|numeric|gt:0',
            'payment_create_info' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'reference_order.required' => 'Reference order is required',
            'charge_id.required' => 'Charge Id is required',
            'status.required' => 'Status must be either success or fail',
            'status.in' => 'Status must be either success or fail',
            'transaction_state.required' => 'Transation State is required',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a positive number',
            'amount.gt' => 'Amount must be a positive number',
            'payment_create_info.array' => 'Payment create info must be an array',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reference_order' => $this->normalizeString($this->input('reference_order', '')),
            'charge_id' => $this->normalizeString($this->input('charge_id', '')),
            'status' => strtolower($this->normalizeString($this->input('status', ''))),
            'transaction_state' => $this->normalizeString($this->input('transaction_state', '')),
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Payment transition validation failed.',
            'data' => null,
            'error' => $validator->errors()->all(),
        ], 422));
    }

    private function normalizeString(mixed $value): string
    {
        return trim((string) $value);
    }
}
