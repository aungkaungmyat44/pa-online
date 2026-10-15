<?php
    $orderReference = $order->order_unique_code;
    $paymentReference = $payment->reference_no;
    $paymentStatus = $payment->status;
    $paymentMethod = $payment->method ?? $order->payment_method;
    $amount = $payment->amount ?? $order->total_amount;
    $currency = $payment->currency ?? 'THB';
    $paidAt = $order->paid_at ?? $payment->updated_at;
    $paidAtLabel = $paidAt ? \Illuminate\Support\Carbon::parse($paidAt)->locale(app()->getLocale())->translatedFormat('d M Y, H:i') : '';
    $errorMessage = trim((string)($message ?? ''));
    $homeUrl = url('home');
    $supportEmail = 'info@sahainsurance.co.th';
    $supportPhone = '02-68-77777';
    $paymentStatusLabel = $paymentStatus !== '' ? __('content.payment_issue_page.statuses.' . strtolower($paymentStatus), [], app()->getLocale()) : '-';
    if ($paymentStatusLabel === 'content.payment_issue_page.statuses.' . strtolower($paymentStatus)) {
        $paymentStatusLabel = ucfirst($paymentStatus);
    }
    $paymentMethodLabel = $paymentMethod !== '' ? __('content.payment_issue_page.methods.' . strtolower($paymentMethod), [], app()->getLocale()) : '-';
    if ($paymentMethodLabel === 'content.payment_issue_page.methods.' . strtolower($paymentMethod)) {
        $paymentMethodLabel = strtoupper($paymentMethod);
    }
?>

@extends('layout.master')

@section('title', __('content.payment_issue_page.title'))

@section('content')
<section class="py-4 payment-issue-page">
    <div class="container">
        <div class="payment-issue-shell">
            <div class="payment-issue-head">
                <h1>{{ __('content.payment_issue_page.heading') }}</h1>
                <p>{{ __('content.payment_issue_page.description') }}</p>
            </div>
            <div class="payment-issue-body">
                <div class="alert alert-warning" role="alert">
                    {{ $errorMessage }}
                </div>

                <table class="payment-issue-meta">
                    <tr>
                        <td>{{ __('content.payment_issue_page.order_reference') }}</td>
                        <td><strong>{{ $orderReference }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ __('content.payment_issue_page.payment_reference') }}</td>
                        <td>{{ $paymentReference }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('content.payment_issue_page.payment_status') }}</td>
                        <td>{{ $paymentStatusLabel }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('content.payment_issue_page.payment_method') }}</td>
                        <td>{{ $paymentMethodLabel }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('content.payment_issue_page.amount') }}</td>
                        <td>{{ number_format($amount, 2) }} {{ $currency }}</td>
                    </tr>
                    <tr>
                        <td>{{ __('content.payment_issue_page.last_updated') }}</td>
                        <td>{{ $paidAtLabel !== '' ? $paidAtLabel : '-' }}</td>
                    </tr>
                </table>

                <div class="payment-issue-note">
                    {{ __('content.payment_issue_page.apology') }}
                    <br><br>
                    {!! sprintf(e(__('content.payment_issue_page.contact')), '<strong>' . e($supportPhone) . '</strong>', '<a href="mailto:' . e($supportEmail) . '">' . e($supportEmail) . '</a>') !!}
                </div>

                <div class="payment-issue-actions">
                    <a href="{{ $homeUrl }}" class="defaultBtn primaryBtn btn">
                        {{ __('content.payment_issue_page.back_home') }} <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    .payment-issue-shell{max-width:760px;margin:0 auto;background:#fff;border:1px solid #e4ebf3;border-radius:16px;box-shadow:0 16px 36px rgb(15 23 42 / .10);overflow:hidden}.payment-issue-head{padding:1.35rem 1.5rem;background:#f8fbff;border-bottom:1px solid #e4ebf3}.payment-issue-head h1{margin:0;color:#1f2937;font-size:1.45rem;font-weight:700}.payment-issue-head p{margin:.35rem 0 0;color:#64748b}.payment-issue-body{padding:1.5rem}.payment-issue-meta{width:100%;border-collapse:collapse;margin:1rem 0}.payment-issue-meta td{padding:.55rem 0;border-bottom:1px solid #eef2f7;color:#334155}.payment-issue-meta td:first-child{width:210px;color:#64748b}.payment-issue-note{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:1rem;color:#334155;line-height:1.65}.payment-issue-actions{display:flex;gap:.75rem;flex-wrap:wrap;justify-content:flex-end;margin-top:1.25rem}
</style>
@endpush