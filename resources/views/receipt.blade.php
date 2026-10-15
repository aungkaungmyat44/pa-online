@extends('layout.master')

@section('title', __('content.receipt.title'))

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

                    <p class="receipt-kicker">{{ __('content.receipt.thank_you') }}</p>
                    <h1>{{ __('content.receipt.issued') }}</h1>

                    <div class="receipt-policy-box">
                        <span>{{ __('content.receipt.policy_number') }}</span>
                        <strong>{{ $policyNumber }}</strong>
                    </div>

                    <div class="alert alert-success receipt-alert" role="alert">
                        {{ __('content.receipt.sent_to_email') }} {{ $email }}
                    </div>

                    <p class="receipt-text">{{ __('content.receipt.check_below') }}</p>

                    <div class="receipt-actions">
                        <a href="{{ route('home') }}" class="otp-btn otp-btn-outline">{{ __('content.receipt.home') }}</a>
                        <a href="{{ route('check-policy', ['policy_number' => $policyNumber]) }}" class="check-premium-submit receipt-policy-btn">
                            {{ __('content.receipt.check_policy') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
