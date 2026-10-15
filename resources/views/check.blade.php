@extends('layout.master')

@section('title', __('content.check.title'))

@section('content')
@php
    $customer = session('customer', []);
    $fieldValue = fn ($key, $default = '') => session()->hasOldInput($key)
        ? old($key)
        : data_get($customer, $key, $default);
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="inquire" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="check-premium-card">
                    <h1>{{ __('content.check.heading') }}</h1>
                    <div class="alert alert-warning d-none js-required-alert" role="alert">
                        {{ __('content.check.required_alert') }}
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <div class="fw-semibold mb-1">{{ __('content.check.recheck_fields') }}</div>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif
                    <form action="{{ route('otp-confirmation') }}" method="POST" id="checkPremiumForm" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">{{ __('content.check.occupation') }}<span class="text-danger"> *</span></label>
                                <select class="form-control @error('occupation') is-invalid @enderror" id="occupation" name="occupation" required>
                                    <option value="">{{ __('content.check.occupation_placeholder') }}</option>
                                    @foreach ($occupations as $key => $occupation)
                                        <option value="{{ $key }}" @selected((string) $fieldValue('occupation') === (string) $key)>{{ $occupation }}</option>
                                    @endforeach
                                </select>
                                @error('occupation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">{{ __('content.check.date_of_birth') }} <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ $fieldValue('date_of_birth') }}" placeholder="{{ __('content.check.date_of_birth_placeholder') }}" autocomplete="off" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('content.check.email') }} <span class="text-danger"> *</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $fieldValue('email') }}" placeholder="{{ __('content.check.email_placeholder') }}">
                                {{-- required --}}
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                {{ __('content.check.underwriting_intro') }} <button type="button" class="terms-link" data-bs-toggle="modal" data-bs-target="#underwritingModal">{{ __('content.check.underwriting_link') }}</button> {{ __('content.check.underwriting_company') }}
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="check-premium-submit">{{ __('content.check.continue') }}</button>
                            </div>
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
        .select2-selection.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 .2rem rgb(220 53 69 / .25);
        }
    </style>
@endpush

@push('js')
<script>
    $(function () {
        const checkPremiumForm = $('#checkPremiumForm');

        function showClientValidation(form) {
            const invalidFields = $(form).find(':input[required]').filter(function () {
                return !this.checkValidity();
            });

            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.js-client-invalid-feedback').remove();

            if (!invalidFields.length) {
                $('.js-required-alert').addClass('d-none');
                return true;
            }

            $('.js-required-alert').removeClass('d-none');

            invalidFields.each(function () {
                const field = $(this);
                const select2Container = field.next('.select2-container');
                const message = this.validationMessage || @json(__('content.check.required_field'));
                field.addClass('is-invalid');
                select2Container.find('.select2-selection').addClass('is-invalid');

                if (!field.next('.invalid-feedback').length && !field.parent().find('.js-client-invalid-feedback').length) {
                    const feedback = $('<div class="invalid-feedback js-client-invalid-feedback d-block"></div>').text(message);

                    if (select2Container.length) {
                        feedback.insertAfter(select2Container);
                    } else {
                        feedback.insertAfter(field);
                    }
                }
            });

            if (!invalidFields.first().hasClass('select2-hidden-accessible')) {
                invalidFields.first()[0].reportValidity();
            } else {
                invalidFields.first().next('.select2-container').find('.select2-selection').trigger('focus');
            }
            invalidFields.first()[0].scrollIntoView({ behavior: 'smooth', block: 'center' });

            return false;
        }

        $('#occupation').select2({
            placeholder: @json(__('content.check.occupation_placeholder')),
            width: '100%'
        });

        $('#date_of_birth').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            maxDate: 0,
            yearRange: '-100:+0'
        });

        checkPremiumForm.on('submit', function (event) {
            if (!showClientValidation(this)) {
                event.preventDefault();
            }
        });

        checkPremiumForm.find(':input[required]').on('input change', function () {
            if (this.checkValidity()) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                $(this).next('.js-client-invalid-feedback').remove();
            }

            if (!checkPremiumForm.find(':input[required]').filter(function () {
                return !this.checkValidity();
            }).length) {
                $('.js-required-alert').addClass('d-none');
            }
        });
    });
</script>
@endpush