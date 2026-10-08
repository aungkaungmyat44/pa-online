@extends('layout.master')

@section('title', 'ใบรับกรมธรรม์')

@section('content')
@php
    $policyNumber = $policyNumber ?? 'PA-0000001';
    $email = $email ?? '';
@endphp

<section id="receipt-section" class="receipt-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="receipt-card">
                    <div class="receipt-success-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <p class="receipt-kicker">ขอบคุณสำหรับการซื้อประกันภัย</p>
                    <h1>ออกกรมธรรม์เรียบร้อยแล้ว</h1>

                    <div class="receipt-policy-box">
                        <span>เลขที่กรมธรรม์ :</span>
                        <strong>{{ $policyNumber }}</strong>
                    </div>

                    <div class="alert alert-success receipt-alert" role="alert">
                        ระบบได้ส่งกรมธรรม์ไปยังอีเมลของท่านแล้ว {{ $email }}
                    </div>

                    <p class="receipt-text">ท่านสามารถตรวจสอบกรมธรรม์ได้จากปุ่มด้านล่าง</p>

                    <div class="receipt-actions">
                        <a href="{{ route('home') }}" class="otp-btn otp-btn-outline">หน้าแรก</a>
                        <a href="{{ route('check-policy', ['policy_number' => $policyNumber]) }}" class="check-premium-submit receipt-policy-btn">
                            ตรวจสอบกรมธรรม์
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
