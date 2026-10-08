<?php
    $orderReference = $order->order_unique_code;
    $paymentReference = $payment->reference_no;
    $paymentStatus = $payment->status;
    $paymentMethod = $payment->method ?? $order->payment_method;
    $amount = $payment->amount ?? $order->total_amount;
    $currency = $payment->currency ?? 'THB';
    $paidAt = $order->paid_at ?? $payment->updated_at;
    $paidAt = date('Y-m-d H:i A');
    $errorMessage = trim((string)($message ?? ''));
    $homeUrl = url('home');
    $supportEmail = 'info@sahainsurance.co.th';
    $supportPhone = '02-68-77777';
?>

@extends('layout.master')

@section('title', 'ดำเนินการชำระเงิน')

@section('content')
<section class="py-4 payment-issue-page">
    <style>
        .payment-issue-shell{max-width:760px;margin:0 auto;background:#fff;border:1px solid #e4ebf3;border-radius:16px;box-shadow:0 16px 36px rgb(15 23 42 / .10);overflow:hidden}.payment-issue-head{padding:1.35rem 1.5rem;background:#f8fbff;border-bottom:1px solid #e4ebf3}.payment-issue-head h1{margin:0;color:#1f2937;font-size:1.45rem;font-weight:700}.payment-issue-head p{margin:.35rem 0 0;color:#64748b}.payment-issue-body{padding:1.5rem}.payment-issue-meta{width:100%;border-collapse:collapse;margin:1rem 0}.payment-issue-meta td{padding:.55rem 0;border-bottom:1px solid #eef2f7;color:#334155}.payment-issue-meta td:first-child{width:210px;color:#64748b}.payment-issue-note{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:1rem;color:#334155;line-height:1.65}.payment-issue-actions{display:flex;gap:.75rem;flex-wrap:wrap;justify-content:flex-end;margin-top:1.25rem}
    </style>

    <div class="container">
        <div class="payment-issue-shell">
            <div class="payment-issue-head">
                <h1>Payment received, policy pending</h1>
                <p>Your payment has been recorded, but the policy could not be issued automatically.</p>
            </div>
            <div class="payment-issue-body">
                <div class="alert alert-warning" role="alert">
                    {{ $errorMessage }}
                </div>

                <table class="payment-issue-meta">
                    <tr>
                        <td>Order reference</td>
                        <td><strong>{{ $orderReference }}</strong></td>
                    </tr>
                    <tr>
                        <td>Payment reference</td>
                        <td>{{ $paymentReference }}</td>
                    </tr>
                    <tr>
                        <td>Payment status</td>
                        <td>{{ $paymentStatus !== '' ? ucfirst($paymentStatus) : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Payment method</td>
                        <td>{{ $paymentMethod !== '' ? strtoupper($paymentMethod) : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td>{{ number_format($amount, 2) }} {{ $currency }}</td>
                    </tr>
                    <tr>
                        <td>Last updated</td>
                        <td>{{ $paidAt !== '' ? $paidAt : '-' }}</td>
                    </tr>
                </table>

                <div class="payment-issue-note">
                    We apologize for the inconvenience. Your payment has been received, but the policy issuance is still being verified by our system. Our team will review this transaction and complete the policy process as soon as possible.
                    <br><br>
                    {!! sprintf('Please contact Sahamongkhon Insurance for assistance and provide the order reference above. Tel: %s or email %s.', '<strong>' . e($supportPhone) . '</strong>', '<a href="mailto:' . e($supportEmail) . '">' . e($supportEmail) . '</a>') !!}
                </div>

                <div class="payment-issue-actions">
                    <a href="{{ $homeUrl }}" class="defaultBtn primaryBtn btn">
                        Back to home <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection