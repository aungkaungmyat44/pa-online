@extends('layout.master')

@section('title', 'Check Policy')

@section('content')
@php
    $policyNumber = $policyNumber ?? 'PA-0000001';
    $orderReference = $orderReference ?? '1';
    $customerName = $customerName ?? '-';
    $productName = $productName ?? 'Personal Accident Insurance';
    $coveragePeriod = $coveragePeriod ?? now()->format('d F Y') . ' - ' . now()->addYear()->format('d F Y');
@endphp

<section id="check-policy-section" class="check-policy-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="check-policy-card">
                    <div class="check-policy-header">
                        <img src="{{ asset('assets/images/saha_logo.png') }}" alt="Sahamongkhon Insurance" class="check-policy-logo">
                        <h1>Sahamongkhon Insurance Public Company Limited</h1>
                        <p>บริษัท สหมงคลประกันภัย จำกัด (มหาชน)</p>
                    </div>
                    <div class="check-policy-title-bar">
                        <span>Electronic Policy (E-Policy)</span>
                        <strong>Policy No. {{ $policyNumber }}</strong>
                    </div>
                    <div class="check-policy-body">
                        <p>Dear valued customer,</p>
                        <p>
                            Thank you for trusting Sahamongkhon Insurance Public Company Limited.
                            Your insurance policy has been successfully issued and is effective according to the coverage period shown below.
                        </p>
                        <div class="check-policy-table-wrap">
                            <table class="table check-policy-table mb-0">
                                <tbody>
                                    <tr>
                                        <th colspan="2">Policy Details</th>
                                    </tr>
                                    <tr>
                                        <td>Order Reference</td>
                                        <td>{{ $orderReference }}</td>
                                    </tr>
                                    <tr>
                                        <td>Policy Number</td>
                                        <td>{{ $policyNumber }}</td>
                                    </tr>
                                    <tr>
                                        <td>Insurance Type</td>
                                        <td>Personal Accident Insurance</td>
                                    </tr>
                                    <tr>
                                        <td>Insured Person</td>
                                        <td>{{ $customerName }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product</td>
                                        <td>{{ $productName }}</td>
                                    </tr>
                                    <tr>
                                        <td>Coverage Period</td>
                                        <td>{{ $coveragePeriod }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p>
                            If you have any questions, please contact Customer Service at 02-68-77777
                            or email <a href="mailto:example@gmail.com">example@gmail.com</a>.
                        </p>
                        <p class="check-policy-signoff">
                            Sincerely,<br>
                            Sahamongkhon Insurance Public Company Limited
                        </p>
                    </div>
                    <div class="check-policy-actions">
                        <a href="{{ route('home') }}" class="otp-btn otp-btn-outline">Home</a>
                        <a href="#" class="check-premium-submit receipt-policy-btn" download>Download Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
