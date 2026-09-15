@extends('layout.master')

@section('title', 'Review Information')

@section('content')
@php
    $review = $review ?? [];
    $healthQuestions = $review['health_questions'] ?? [];
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
                    <h1>Review Information</h1>

                    <div class="review-section">
                        <h2>Chosen Plan</h2>
                        <div class="review-grid">
                            <div>
                                <span>Selected Plan</span>
                                <strong>{{ $formatValue($review['selected_plan'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Occupation</span>
                                <strong>{{ $formatValue($review['occupation'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>Personal Info</h2>
                        <div class="review-grid">
                            <div>
                                <span>Prefix</span>
                                <strong>{{ $formatValue($review['prefix'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>First Name</span>
                                <strong>{{ $formatValue($review['first_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Last Name</span>
                                <strong>{{ $formatValue($review['last_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Nationality</span>
                                <strong>{{ $formatValue($review['nationality'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Identity Type</span>
                                <strong>{{ $formatValue($review['identity_type'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Identity Number</span>
                                <strong>{{ $formatValue($review['identity_number'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Date of Birth</span>
                                <strong>{{ $formatValue($review['date_of_birth'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>Contact Info</h2>
                        <div class="review-grid">
                            <div>
                                <span>Email</span>
                                <strong>{{ $formatValue($review['email'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Phone Number</span>
                                <strong>{{ $formatValue($review['phone_number'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>Address Information</h2>
                        <div class="review-grid">
                            <div class="review-wide">
                                <span>Full Address</span>
                                <strong>{{ $formatValue($review['full_address'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Province</span>
                                <strong>{{ $formatValue($review['province_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>District</span>
                                <strong>{{ $formatValue($review['district_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Subdistrict</span>
                                <strong>{{ $formatValue($review['subdistrict_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Zipcode</span>
                                <strong>{{ $formatValue($review['zipcode'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>Health Questions</h2>
                        <div class="review-grid">
                            @forelse ($healthQuestions as $question => $answer)
                                <div>
                                    <span>{{ ucwords(str_replace('_', ' ', $question)) }}</span>
                                    <strong>{{ $answer }}</strong>
                                </div>
                            @empty
                                <div>
                                    <span>Health Answers</span>
                                    <strong>-</strong>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>Beneficiary & Consent</h2>
                        <div class="review-grid">
                            <div>
                                <span>Beneficiary</span>
                                <strong>{{ $formatValue($review['beneficiary'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>Personal Data Collection</span>
                                <strong>{{ isset($review['personal_data_collection']) ? 'Accepted' : '-' }}</strong>
                            </div>
                            <div>
                                <span>Sensitive Personal Data</span>
                                <strong>{{ isset($review['sensitive_personal_data']) ? 'Accepted' : '-' }}</strong>
                            </div>
                            <div>
                                <span>Marketing Consent</span>
                                <strong>{{ isset($review['marketing_consent']) ? 'Accepted' : 'Not accepted' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section policy-document-section">
                        <h2>How would you like to achieve the policy documents</h2>
                        <div class="policy-document-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="policy_delivery[]" id="policy_delivery_email" value="Receive policy via email" form="reviewConfirmForm">
                                <label class="form-check-label" for="policy_delivery_email">Receive policy via email</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="policy_delivery[]" id="policy_delivery_mail" value="Receive policy with mail box within 15 days" form="reviewConfirmForm">
                                <label class="form-check-label" for="policy_delivery_mail">Receive policy from mail box within 15 days</label>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment-methods') }}" method="post" class="review-confirm-form" id="reviewConfirmForm">
                        @csrf
                        @foreach ($review as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $childKey => $childValue)
                                    <input type="hidden" name="{{ $key }}[{{ $childKey }}]" value="{{ $childValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <div class="health-question-actions">
                            <a href="{{ route('check-premium') }}" class="otp-btn otp-btn-outline">Back</a>
                            <button type="submit" class="check-premium-submit">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
