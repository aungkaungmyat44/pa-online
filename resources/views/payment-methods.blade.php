@extends('layout.master')

@section('title', 'Payment Methods')

@section('content')
@php
    $payment = $payment ?? [];
    $selectedPlan = $payment['selected_plan'] ?? null;
    $planAmounts = [
        'Plan 1' => '200,000 Baht',
        'Plan 2' => '500,000 Baht',
        'Plan 3' => '800,000 Baht',
        'Plan 4' => '1,000,000 Baht',
        'Plan 5' => '1,500,000 Baht',
    ];
    $premiumAmountLabel = '2,000 Baht';

    $paymentMethods = [
        [
            'value' => 'card',
            'title' => 'Credit / Debit Card',
            'description' => 'Pay securely with Mastercard, Visa, or JCB card.',
            'image' => 'assets/images/master.png',
            'image_alt' => 'Mastercard logo',
        ],
        [
            'value' => 'thai_qr',
            'title' => 'Thai QR Payment',
            'description' => 'Scan a QR code with your mobile banking application.',
            'image' => 'assets/images/thai_qr.png',
            'image_alt' => 'Thai QR logo',
        ],
        [
            'value' => 'payment_link',
            'title' => 'KBank Payment Link',
            'description' => 'Receive a payment link and complete payment through KBank.',
            'image' => 'assets/images/kbank.png',
            'image_alt' => 'KBank logo',
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
                <form action="{{ route('payment-process') }}" method="post" class="check-premium-card payment-methods-card">
                    @csrf
                    <h1>Payment Methods</h1>
                    <p class="payment-methods-subtitle">Choose one payment method to continue. Images can be added to each payment type later.</p>

                    <div class="payment-summary-box">
                        <div>
                            <span>Selected Plan</span>
                            <strong>{{ $selectedPlan ?? '-' }}</strong>
                        </div>
                        <div>
                            <span>Coverage Amount</span>
                            <strong>{{ $planAmounts[$selectedPlan] ?? '-' }}</strong>
                        </div>
                        <div>
                            <span>Premium Amount</span>
                            <strong>{{ $premiumAmountLabel }}</strong>
                        </div>
                    </div>

                    @foreach ($payment as $key => $value)
                        @continue($key === '_token')
                        @if (is_array($value))
                            @foreach ($value as $childKey => $childValue)
                                <input type="hidden" name="{{ $key }}[{{ $childKey }}]" value="{{ $childValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

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
                        <button
                            type="submit"
                            class="otp-btn otp-btn-outline"
                            formaction="{{ route('review-information') }}"
                            formmethod="post"
                            formnovalidate
                        >Back</button>
                        <button type="submit" class="check-premium-submit">Payment Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

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
@endsection
