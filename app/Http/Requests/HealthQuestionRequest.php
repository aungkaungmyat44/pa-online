<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class HealthQuestionRequest extends FormRequest
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
            'health_questions' => ['required', 'array'],
            'health_questions.other_insurance' => ['required', 'string'],
            'health_questions.insurance_declined' => ['required', 'string'],
            'health_questions.accident_hospitalized' => ['required', 'string'],
            'health_questions.impairment_or_drug_history' => ['required', 'string'],
            'health_questions.medical_condition_history' => ['required', 'string'],
            'health_questions.*' => ['required', 'string'],
            'terms' => ['accepted'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $eligibleAnswers = [
                'other_insurance' => 'ไม่มี',
                'insurance_declined' => 'ไม่เคย',
                'accident_hospitalized' => 'ไม่เคย',
                'impairment_or_drug_history' => 'ไม่เคย',
                'medical_condition_history' => 'ไม่เคย',
            ];

            $messages = [
                'other_insurance' => 'Sorry if you have an active or ongoing policy, we cannot sell this insurance.',
                'insurance_declined' => 'Sorry if you have been declined, cancelled, or charged extra premium by an insurer, we cannot sell this insurance.',
                'accident_hospitalized' => 'Sorry if you were hospitalized from an accident within the past 2 years, we cannot sell this insurance.',
                'impairment_or_drug_history' => 'Sorry if you have impairment, nervous system history, disability, drug history, or drug-related conviction, we cannot sell this insurance.',
                'medical_condition_history' => 'Sorry if you have the listed medical condition history, we cannot sell this insurance.',
            ];

            foreach ($eligibleAnswers as $key => $eligibleAnswer) {
                $answer = $this->input("health_questions.$key");

                if ($answer !== null && $answer !== $eligibleAnswer) {
                    $validator->errors()->add("health_questions.$key", $messages[$key]);
                }
            }
        });
    }
}
