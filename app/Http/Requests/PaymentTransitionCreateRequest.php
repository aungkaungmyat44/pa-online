<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentTransitionCreateRequest extends FormRequest
{
    private bool $invalidPaymentCreateInfoJson = false;

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
            'reference_order.required' => 'reference_order is required',
            'charge_id.required' => 'charge_id is required',
            'status.required' => 'status must be either success or fail',
            'status.in' => 'status must be either success or fail',
            'transaction_state.required' => 'transaction_state is required',
            'amount.required' => 'amount is required',
            'amount.numeric' => 'amount must be a positive number',
            'amount.gt' => 'amount must be a positive number',
            'payment_create_info.array' => 'payment_create_info must be an array',
        ];
    }

    protected function prepareForValidation(): void
    {
        $paymentCreateInfo = $this->input('payment_create_info', []);

        if (is_string($paymentCreateInfo) and $paymentCreateInfo !== '') {
            $decoded = json_decode($paymentCreateInfo, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $paymentCreateInfo = $decoded;
            } else {
                $this->invalidPaymentCreateInfoJson = true;
                $paymentCreateInfo = [];
            }
        }

        $amount = $this->input('amount');
        $amountFloat = filter_var($amount, FILTER_VALIDATE_FLOAT);

        $this->merge([
            'reference_order' => $this->normalizeString($this->input('reference_order', '')),
            'charge_id' => $this->normalizeString($this->input('charge_id', '')),
            'status' => strtolower($this->normalizeString($this->input('status', ''))),
            'transaction_state' => $this->normalizeString($this->input('transaction_state', '')),
            'amount' => $amountFloat !== false && $amountFloat > 0
                ? number_format($amountFloat, 2, '.', '')
                : $amount,
            'payment_create_info' => $paymentCreateInfo,
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->invalidPaymentCreateInfoJson) {
                $validator->errors()->add(
                    'payment_create_info',
                    'payment_create_info must be valid JSON'
                );
            }
        });
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
