@extends('layout.master')

@section('title', 'Policy Receipt')

@section('content')
@php
    $policyNumber = $policyNumber ?? 'PA-0000001';
@endphp

<section id="receipt-section" class="receipt-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="receipt-card">
                    <div class="receipt-success-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <p class="receipt-kicker">Thank you for purchasing</p>
                    <h1>Policy has been successfully issued!</h1>

                    <div class="receipt-policy-box">
                        <span>Policy number :</span>
                        <strong>{{ $policyNumber }}</strong>
                    </div>

                    <div class="alert alert-success receipt-alert" role="alert">
                        Policy Email has been sent to your mail box
                    </div>

                    <p class="receipt-text">Check policy with the following button</p>

                    <div class="receipt-actions">
                        <a href="{{ route('home') }}" class="otp-btn otp-btn-outline">Home</a>
                        <a href="{{ route('check-policy', ['policy_number' => $policyNumber]) }}" class="check-premium-submit receipt-policy-btn">
                            Check Policy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
