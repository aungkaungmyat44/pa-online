@extends('layout.master')

@section('title', 'วิธีชำระเงิน')

@section('content')
@php
    $payment = $payment ?? [];
    $selectedPlan = $payment['selected_plan'] ?? null;
    $coverageAmount = $payment['coverage_amount'] ?? config('coverage_plans.rows.0.amounts.0');
    $coverageAmountLabel = filled($coverageAmount) ? number_format((int) str_replace(',', '', $coverageAmount)) . ' บาท' : '-';
    $premiumAmountLabel = '888 บาท';

    $paymentMethods = [
        [
            'value' => 'card',
            'title' => 'บัตรเครดิต / บัตรเดบิต',
            'description' => 'ชำระเงินอย่างปลอดภัยด้วยบัตร Mastercard, Visa หรือ JCB',
            'image' => 'assets/images/master.png',
            'image_alt' => 'โลโก้ Mastercard',
        ],
        [
            'value' => 'thai_qr',
            'title' => 'ชำระเงินด้วย Thai QR',
            'description' => 'สแกน QR Code ผ่านแอปพลิเคชันธนาคารบนมือถือ',
            'image' => 'assets/images/thai_qr.png',
            'image_alt' => 'โลโก้ Thai QR',
        ],
        [
            'value' => 'payment_link',
            'title' => 'ลิงก์ชำระเงิน KBank',
            'description' => 'รับลิงก์ชำระเงินและดำเนินการชำระผ่าน KBank',
            'image' => 'assets/images/kbank.png',
            'image_alt' => 'โลโก้ KBank',
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
                    <h1>วิธีชำระเงิน</h1>
                    <p class="payment-methods-subtitle">เลือกวิธีชำระเงินหนึ่งรายการเพื่อดำเนินการต่อ</p>

                    <div class="payment-summary-box">
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
                    </div>

                    @foreach ($payment as $key => $value)
                        @continue($key === '_token' || $key === 'coverage_amount')
                        @if (is_array($value))
                            @foreach ($value as $childKey => $childValue)
                                <input type="hidden" name="{{ $key }}[{{ $childKey }}]" value="{{ $childValue }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <input type="hidden" name="coverage_amount" value="{{ $coverageAmount }}">

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
                        >ย้อนกลับ</button>
                        <button type="submit" class="check-premium-submit">ดำเนินการชำระเงิน</button>
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
