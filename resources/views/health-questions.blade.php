@extends('layout.master')

@section('title', __('content.health_questions.title'))

@section('content')
@php
    $questions = __('content.health_questions.items');
    $answerValues = [
        'other_insurance' => ['no' => 'ไม่มี', 'yes' => 'มี'],
        'insurance_declined' => ['no' => 'ไม่เคย', 'yes' => 'เคย'],
        'accident_hospitalized' => ['no' => 'ไม่เคย', 'yes' => 'เคย'],
        'impairment_or_drug_history' => ['no' => 'ไม่เคย', 'yes' => 'เคย'],
        'medical_condition_history' => ['no' => 'ไม่เคย', 'yes' => 'เคย'],
    ];
    $answers = old('health_questions', $customer['health_questions'] ?? []);
    $termsAccepted = old('terms', $customer['health_terms'] ?? false);
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="questionnaire" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="check-premium-card health-question-card">
                    <h1>{{ __('content.health_questions.heading') }}</h1>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <div class="fw-semibold mb-1">{{ __('content.check.recheck_fields') }}</div>
                        </div>
                    @endif
                    <form action="{{ route('save-health-questions') }}" method="POST">
                        @csrf
                        <input type="hidden" name="occupation" value="{{ $customer['occupation'] ?? '' }}">
                        <input type="hidden" name="email" value="{{ $customer['email'] ?? '' }}">
                        <input type="hidden" name="date_of_birth" value="{{ $customer['date_of_birth'] ?? '' }}">
                        <input type="hidden" name="otp_code" value="{{ $customer['otp_code'] ?? '' }}">
                        <input type="hidden" name="selected_plan" value="{{ $selectedPlan['id'] }}">

                        <div class="health-plan-section">
                            <h2>{{ __('content.coverage_table_heading') }}</h2>
                            <div class="health-plan-card health-plan-card-readonly">
                                <div class="health-plan-summary">
                                    <div>
                                        <h6>{{ app()->getLocale() === 'en' ? data_get($selectedPlan, 'name_en') : data_get($selectedPlan, 'name_th') }}</h6>
                                    </div>
                                    <span>{{ __('content.health_questions.auto_selected') }}</span>
                                </div>
                                <ul>
                                    @foreach ($coverageRows as $row)
                                        <li>
                                            {{ $row['label'] }}:
                                            {{ $row['amount'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="health-question-list">
                            @foreach ($questions as $key => $question)
                                <div class="health-question-item">
                                    <p>{{ $loop->iteration }}. {{ $question['text'] }}</p>
                                    <div class="health-radio-group" role="radiogroup" aria-label="{{ $question['text'] }}">
                                        @foreach ($question['options'] as $optionKey => $optionLabel)
                                            @php
                                                $optionId = $key . '_' . $loop->index;
                                                $optionValue = $answerValues[$key][$optionKey];
                                            @endphp
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input @error("health_questions.$key") is-invalid @enderror" type="radio" name="health_questions[{{ $key }}]" id="{{ $optionId }}" value="{{ $optionValue }}" @checked(($answers[$key] ?? null) === $optionValue) required>
                                                <label class="form-check-label" for="{{ $optionId }}">{{ $optionLabel }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error("health_questions.$key")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div class="form-check check-premium-terms mt-3">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" value="1" id="terms" name="terms" @checked($termsAccepted) required>
                            <label class="form-check-label" for="terms">
                                {{ __('content.health_questions.terms_intro') }} <button type="button" class="terms-link" data-bs-toggle="modal" data-bs-target="#underwritingModal">{{ __('content.check.underwriting_link') }}</button> {{ __('content.check.underwriting_company') }}
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="health-question-actions">
                            <a href="{{ route('check-premium') }}" class="otp-btn otp-btn-outline">{{ __('content.otp.back') }}</a>
                            <button type="submit" id="submitButton" class="check-premium-submit" disabled>
                                {{ __('content.check.continue') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="underwritingModal" tabindex="-1" aria-labelledby="underwritingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content underwriting-modal">
            <div class="modal-header">
                <h2 class="modal-title" id="underwritingModalLabel">{{ __('content.check.underwriting_link') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section>
                    <h6>{{ __('content.check.underwriting_conditions_heading') }}</h6>
                    <ul>
                        @foreach (__('content.check.underwriting_conditions') as $condition)
                            <li>{{ $condition }}</li>
                        @endforeach
                    </ul>
                </section>
                <section>
                    <h6>{{ __('content.check.exclusions_heading') }}</h6>
                    <ul>
                        @foreach (__('content.check.exclusions') as $exclusion)
                            <li>{{ $exclusion }}</li>
                        @endforeach
                    </ul>
                </section>
                <section>
                    <h6>{{ __('content.check.excluded_occupations_heading') }}</h6>
                    <p>{{ __('content.check.excluded_occupations') }}</p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .check-premium-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush

@push('js')
<script>
    $(function () {
        function updateSubmitButton() {
            $('#submitButton').prop('disabled', !$('#terms').is(':checked'));
        }

        $('#terms').on('change', updateSubmitButton);
        $(window).on('pageshow', updateSubmitButton);

        updateSubmitButton();
    });
</script>
@endpush
