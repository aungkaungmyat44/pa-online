@extends('layout.master')

@section('title', 'Payment Process')

@section('content')
@php
    $payment = $payment ?? [];
    $selectedPlan = $payment['selected_plan'] ?? null;
    $planAmounts = [
        'Plan 1' => 200000,
        'Plan 2' => 500000,
        'Plan 3' => 800000,
        'Plan 4' => 1000000,
        'Plan 5' => 1500000,
    ];
    $coverageAmount = $planAmounts[$selectedPlan] ?? 0;
    $coverageAmountLabel = $coverageAmount > 0 ? number_format($coverageAmount) . ' Baht' : '-';
    $premiumAmount = 2000;
    $premiumAmountLabel = number_format($premiumAmount) . ' Baht';
    $productName = $selectedPlan ?? 'Personal Accident Insurance';
    $orderIdValue = '1';
    $customerName = trim(implode(' ', array_filter([
        $payment['prefix'] ?? null,
        $payment['first_name'] ?? null,
        $payment['last_name'] ?? null,
    ])));
    $submittedDate = now()->format('d M Y, H:i');
@endphp

<section id="check-premium-section" class="payment-process-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ol class="mi-progress mi-progress-six" aria-label="Check premium progress">
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker"><i class="bi bi-pencil-square"></i></span>
                        <span class="mi-progress-label">Inquire</span>
                    </li>
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker"><i class="bi bi-mailbox-flag"></i></span>
                        <span class="mi-progress-label">OTP Verification</span>
                    </li>
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker"><i class="bi bi-ui-checks-grid"></i></span>
                        <span class="mi-progress-label">Questionnaire</span>
                    </li>
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker"><i class="bi bi-person-vcard"></i></span>
                        <span class="mi-progress-label">Personal Information</span>
                    </li>
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker"><i class="bi bi-file-earmark-check"></i></span>
                        <span class="mi-progress-label">Review</span>
                    </li>
                    <li class="mi-progress-step is-active">
                        <span class="mi-progress-marker"><i class="bi bi-credit-card"></i></span>
                        <span class="mi-progress-label">Payment</span>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="payment-process-card">
                    <div class="payment-animation-wrap">
                        <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
                        <dotlottie-player
                            src="{{ asset('assets/animations/animation.lottie') }}"
                            background="transparent"
                            speed="1"
                            loop
                            autoplay>
                        </dotlottie-player>
                    </div>

                    <span class="payment-process-badge">
                        <i class="bi bi-credit-card-2-front"></i>
                        Card Payment
                    </span>

                    <h1>Processing Payment</h1>
                    <p>Please complete your card payment within <strong>3 minutes</strong>. The payment status will update automatically.</p>

                    <div class="payment-process-details">
                        <div>
                            <span>Payment Method</span>
                            <strong>Credit / Debit Card</strong>
                        </div>
                        <div>
                            <span>Selected Plan</span>
                            <strong>{{ $selectedPlan ?? '-' }}</strong>
                        </div>
                        <div>
                            <span>Coverage Amount</span>
                            <strong>{{ $coverageAmountLabel }}</strong>
                        </div>
                        <div>
                            <span>Premium Amount</span>
                            <strong>{{ $premiumAmountLabel }}</strong>
                        </div>
                        <div>
                            <span>Customer Name</span>
                            <strong>{{ $customerName !== '' ? $customerName : '-' }}</strong>
                        </div>
                        <div>
                            <span>Submitted Date</span>
                            <strong>{{ $submittedDate }}</strong>
                        </div>
                    </div>

                    <div class="health-question-actions payment-process-actions">
                        <form action="{{ route('payment-methods') }}" method="post" class="payment-process-back-form">
                            @csrf
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
                            <button type="submit" class="otp-btn otp-btn-outline">Back</button>
                        </form>

                        <form method="POST" action="{{ route('checkout') }}" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="product_id" id="checkout_product_id" value="1">
                            <input type="hidden" name="product_name" id="checkout_product_name" value="{{ $productName }}">
                            <input type="hidden" name="product_price" id="checkout_product_price" value="{{ $premiumAmount }}">
                            <input type="hidden" name="order_id" value="{{ $orderIdValue }}">
                            <script type="text/javascript" id="kpay-script"
                                src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                                data-apikey="pkey_test_22500dOmwrzgdQXT7BZghVMHnHijHyJB9JN2J"
                                data-amount="{{ $premiumAmount }}"
                                data-currency="THB"
                                data-payment-methods="card"
                                data-name="SAHAMONGKHON INSURANCE"
                                data-mid="401926120298001">
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
