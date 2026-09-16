@extends('layout.master')

@section('title', 'ดำเนินการชำระเงิน')

@section('content')
@php
    $payment = $payment ?? [];
    $selectedPlan = $payment['selected_plan'] ?? null;
    $coverageAmount = $payment['coverage_amount'] ?? config('coverage_plans.rows.0.amounts.0');
    $coverageAmountValue = (int) str_replace(',', '', $coverageAmount);
    $coverageAmountLabel = $coverageAmountValue > 0 ? number_format($coverageAmountValue) . ' บาท' : '-';
    $premiumAmount = 888;
    $premiumAmountLabel = number_format($premiumAmount) . ' บาท';
    $productName = $selectedPlan ?? 'ประกันภัยอุบัติเหตุส่วนบุคคล';
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
                <x-progress-steps active="payment" />
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
                        ชำระเงินด้วยบัตร
                    </span>

                    <h1>กำลังดำเนินการชำระเงิน</h1>
                    <p>กรุณาชำระเงินด้วยบัตรให้เสร็จสิ้นภายใน <strong>3 นาที</strong> ระบบจะอัปเดตสถานะการชำระเงินโดยอัตโนมัติ</p>

                    <div class="payment-process-details">
                        <div>
                            <span>วิธีชำระเงิน</span>
                            <strong>บัตรเครดิต / บัตรเดบิต</strong>
                        </div>
                        <div>
                            <span>แผนประกัน</span>
                            <strong>{{ $selectedPlan ?? '-' }}</strong>
                        </div>
                        <div>
                            <span>จำนวนเงินความคุ้มครอง</span>
                            <strong>{{ $coverageAmountLabel }}</strong>
                        </div>
                        <div>
                            <span>จำนวนเบี้ยประกัน</span>
                            <strong>{{ $premiumAmountLabel }}</strong>
                        </div>
                        <div>
                            <span>ชื่อผู้เอาประกันภัย</span>
                            <strong>{{ $customerName !== '' ? $customerName : '-' }}</strong>
                        </div>
                        <div>
                            <span>วันที่ทำรายการ</span>
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
                            <button type="submit" class="otp-btn otp-btn-outline">ย้อนกลับ</button>
                        </form>

                        <form method="POST" action="{{ route('checkout') }}" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="product_id" id="checkout_product_id" value="1">
                            <input type="hidden" name="product_name" id="checkout_product_name" value="{{ $productName }}">
                            <input type="hidden" name="product_price" id="checkout_product_price" value="{{ $premiumAmount }}">
                            <input type="hidden" name="order_id" value="{{ $orderIdValue }}">
                            <input type="hidden" name="email" value="{{ $payment['email'] ?? '' }}">
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
