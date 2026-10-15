@extends('layout.master')

@section('title', __('content.review_information.title'))

@section('content')
@php
    $review = $customer ?? [];
    $healthQuestions = $review['health_questions'] ?? [];
    $isEnglish = app()->getLocale() === 'en';
    $cardTypeLabels = __('content.information.document_types');
    $healthQuestionItems = __('content.health_questions.items');
    $healthAnswerValues = [
        'no' => ['ไม่มี', 'ไม่เคย'],
        'yes' => ['มี', 'เคย'],
    ];
    $translateHealthAnswer = function ($question, $answer) use ($healthQuestionItems, $healthAnswerValues) {
        foreach (data_get($healthQuestionItems, "$question.options", []) as $option => $label) {
            if (in_array($answer, $healthAnswerValues[$option] ?? [], true)) {
                return $label;
            }
        }

        return $answer;
    };
    $formatValue = function ($value) {
        if (is_array($value)) {
            return implode(', ', $value);
        }

        return filled($value) ? $value : '-';
    };
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="review" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="check-premium-card review-information-card">
                    <h1>{{ __('content.review_information.title') }}</h1>

                    <div class="review-section">
                        <h2>{{ __('content.review_information.selected_plan') }}</h2>
                        <div class="review-grid">
                            <div>
                                <span>{{ __('content.review_information.plan') }}</span>
                                <strong>{{ $formatValue($isEnglish ? ($review['plan']['name_en'] ?? $review['plan']['name_th'] ?? null) : ($review['plan']['name_th'] ?? null)) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.review_information.occupation') }}</span>
                                <strong>{{ $formatValue($isEnglish ? ($occupation->occupation_en ?? $occupation->occupation_th ?? null) : ($occupation->occupation_th ?? null)) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>{{ __('content.information.personal_details') }}</h2>
                        <div class="review-grid">
                            <div>
                                <span>{{ __('content.information.fields.prefix') }}</span>
                                <strong>{{ $formatValue($title->display_name ?? $review['information']['title_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.first_name') }}</span>
                                <strong>{{ $formatValue($review['information']['first_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.last_name') }}</span>
                                <strong>{{ $formatValue($review['information']['last_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.nationality') }}</span>
                                <strong>{{ $formatValue($isEnglish ? ($country->ct_nameeng ?? $country->ct_nameth ?? null) : ($country->ct_nameth ?? null)) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.identity_type') }}</span>
                                <strong>{{ $formatValue($cardTypeLabels[$review['information']['identity_type'] ?? ''] ?? ($review['information']['identity_type'] ?? null)) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.identity_number') }}</span>
                                <strong>{{ $formatValue($review['information']['identity_number'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.date_of_birth') }}</span>
                                <strong>{{ $formatValue($review['information']['date_of_birth'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>{{ __('content.information.contact_details') }}</h2>
                        <div class="review-grid">
                            <div>
                                <span>{{ __('content.information.fields.email') }}</span>
                                <strong>{{ $formatValue($review['email'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.phone_number') }}</span>
                                <strong>{{ $formatValue($review['information']['phone_number'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>{{ __('content.information.address_details') }}</h2>
                        <div class="review-grid">
                            <div class="review-wide">
                                <span>{{ __('content.information.fields.full_address') }}</span>
                                <strong>{{ $formatValue($review['information']['full_address'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.province') }}</span>
                                <strong>{{ $formatValue($province->display_name ?? $review['information']['province_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.district') }}</span>
                                <strong>{{ $formatValue($district->display_name ?? $review['information']['district_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.subdistrict') }}</span>
                                <strong>{{ $formatValue($subdistrict->display_name ?? $review['information']['subdistrict_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.zipcode') }}</span>
                                <strong>{{ $formatValue($review['information']['zipcode'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>{{ __('content.health_questions.heading') }}</h2>
                        <div class="review-grid">
                            @forelse ($healthQuestions as $question => $answer)
                                <div>
                                    <span>{{ data_get($healthQuestionItems, "$question.text", ucwords(str_replace('_', ' ', $question))) }}</span>
                                    <strong>{{ $translateHealthAnswer($question, $answer) }}</strong>
                                </div>
                            @empty
                                <div>
                                    <span>{{ __('content.review_information.health_answers') }}</span>
                                    <strong>-</strong>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>{{ __('content.review_information.beneficiary_and_consent') }}</h2>
                        <div class="review-grid">
                            <div>
                                <span>{{ __('content.information.fields.beneficiary') }}</span>
                                <strong>{{ $formatValue($review['information']['beneficiary'] ?? __('content.review_information.legal_heir')) }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.personal_data_collection') }}</span>
                                <strong>{{ isset($review['information']['personal_data_collection']) ? __('content.review_information.accepted') : '-' }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.sensitive_personal_data') }}</span>
                                <strong>{{ isset($review['information']['sensitive_personal_data']) ? __('content.review_information.accepted') : '-' }}</strong>
                            </div>
                            <div>
                                <span>{{ __('content.information.fields.marketing_consent') }}</span>
                                <strong>{{ isset($review['information']['marketing_consent']) ? __('content.review_information.accepted') : __('content.review_information.declined') }}</strong>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('proceed-payment') }}" method="post" class="review-confirm-form" id="reviewConfirmForm">
                        @csrf
                        <div class="review-section policy-document-section">
                            <h2>{{ __('content.review_information.policy_delivery_heading') }}</h2>
                            <div class="policy-document-options">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="policy_delivery[]" id="policy_delivery_email" value="รับกรมธรรม์ทางอีเมล" checked aria-readonly="true" onclick="return false;" onkeydown="return false;">
                                    <label class="form-check-label" for="policy_delivery_email">{{ __('content.review_information.policy_delivery_email') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="policy_delivery[]" id="policy_delivery_mail" value="รับกรมธรรม์ทางไปรษณีย์ภายใน 15 วัน">
                                    <label class="form-check-label" for="policy_delivery_mail">{{ __('content.review_information.policy_delivery_mail') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="health-question-actions">
                            <a href="{{ route('show-information-form') }}" class="otp-btn otp-btn-outline">{{ __('content.otp.back') }}</a>
                            <button type="submit" class="check-premium-submit">{{ __('content.review_information.confirm') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
