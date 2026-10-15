@extends('layout.master')

@section('title', __('content.payment_methods.title'))

@section('content')
@php
    $selectedPlan = $order['plan'] ?? null;
    $coverageAmount = $order['coverage_amount'] ?? config('coverage_plans.rows.0.amounts.0');
    $isEnglish = app()->getLocale() === 'en';
    $currencyLabel = __('content.payment_methods.currency');
    $coverageAmountLabel = number_format((int) str_replace(',', '', $selectedPlan['death_coverage'])) . ' ' . $currencyLabel;
    $premiumAmountLabel = '888 ' . $currencyLabel;

    $paymentMethods = [
        [
            'value' => 'card',
            'title' => __('content.payment_methods.card_title'),
            'description' => __('content.payment_methods.card_description'),
            'image' => 'assets/images/master.png',
            'image_alt' => __('content.payment_methods.card_image_alt'),
        ],
        [
            'value' => 'qr',
            'title' => __('content.payment_methods.qr_title'),
            'description' => __('content.payment_methods.qr_description'),
            'image' => 'assets/images/thai_qr.png',
            'image_alt' => __('content.payment_methods.qr_image_alt'),
        ],
        [
            'value' => 'link',
            'title' => __('content.payment_methods.kbank_title'),
            'description' => __('content.payment_methods.kbank_description'),
            'image' => 'assets/images/kbank.png',
            'image_alt' => __('content.payment_methods.kbank_image_alt'),
        ],
    ];
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="payment" />
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ route('request-payment') }}" method="post" class="check-premium-card payment-methods-card">
                    @csrf
                    <h1>{{ __('content.payment_methods.title') }}</h1>
                    <p class="payment-methods-subtitle">{{ __('content.payment_methods.subtitle') }}</p>

                    <div class="payment-summary-box">
                        <div>
                            <span>{{ __('content.payment_methods.plan') }}</span>
                            <strong>{{ $selectedPlan[$isEnglish ? 'name_en' : 'name_th'] ?? '-' }}</strong>
                        </div>
                        <div>
                            <span>{{ __('content.payment_methods.coverage_amount') }}</span>
                            <strong>{{ $coverageAmountLabel }}</strong>
                        </div>
                        <div>
                            <span>{{ __('content.payment_methods.premium_amount') }}</span>
                            <strong>{{ $premiumAmountLabel }}</strong>
                        </div>
                    </div>
                    <div class="row g-3 payment-method-row">
                        @foreach ($paymentMethods as $index => $method)
                            <div class="col-md-4">
                                <label class="payment-method-card {{ $index === 0 ? 'is-active' : '' }}" for="payment_method_{{ $method['value'] }}">
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        id="payment_method_{{ $method['value'] }}"
                                        value="{{ $method['value'] }}"
                                        {{ $index === 0 ? 'checked' : '' }}
                                        required
                                    >
                                    <span class="payment-method-radio"></span>
                                    <span class="payment-method-image-placeholder">
                                        <img src="{{ asset($method['image']) }}" alt="{{ $method['image_alt'] }}">
                                    </span>
                                    <span class="payment-method-title">{{ $method['title'] }}</span>
                                    <span class="payment-method-text">{{ $method['description'] }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="health-question-actions">
                        <a href="{{ route('show-review-information') }}" class="otp-btn otp-btn-outline">{{ __('content.payment_methods.back') }}</a>
                        <button type="submit" class="check-premium-submit">{{ __('content.payment_methods.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
<script>
    $(function () {
        $('.payment-method-card').on('click keydown', function (event) {
            if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            if (event.type === 'keydown') {
                event.preventDefault();
            }

            $('.payment-method-card').removeClass('is-active');
            $(this).addClass('is-active');
            $(this).find('input[type="radio"]').prop('checked', true);
        });
    });
</script>
@endpush