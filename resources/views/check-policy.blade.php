@extends('layout.master')

@section('title', __('content.check_policy.title'))

@section('content')
@php
    $policyNumber = 'MISC-PAI26-0417-09031';
    $orderReference = '#00001';
    $isEnglish = app()->getLocale() === 'en';
    $customerName = $isEnglish ? 'Mr. William' : 'คุณ William';
    $productName = __('content.check_policy.product_name');
    $coveragePeriod = $coveragePeriod ?? now()->locale(app()->getLocale())->translatedFormat('d F Y') . ' - ' . now()->addYear()->locale(app()->getLocale())->translatedFormat('d F Y');
@endphp

<section id="check-policy-section" class="check-policy-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="check-policy-card">
                    <div class="check-policy-header">
                        <img src="{{ asset('assets/images/saha_logo.png') }}" alt="{{ __('content.check_policy.company_name') }}" class="check-policy-logo">
                        <h1>{{ __('content.check_policy.company_name') }}</h1>
                        <p>{{ __('content.check_policy.company_short_name') }}</p>
                    </div>
                    <div class="check-policy-title-bar">
                        <span>{{ __('content.check_policy.e_policy') }}</span>
                        <strong>{{ __('content.check_policy.policy_number') }} {{ $policyNumber }}</strong>
                    </div>
                    <div class="check-policy-body">
                        <div class="check-policy-table-wrap">
                            <table class="check-policy-meta">
                                <tr>
                                    <td>{{ __('content.check_policy.reference_number') }}</td>
                                    <td>{{ $orderReference !== '' ? $orderReference : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('content.check_policy.insured') }}</td>
                                    <td>{{ $customerName }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('content.check_policy.product') }}</td>
                                    <td>{{ $productName }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('content.check_policy.coverage_period') }}</td>
                                    <td>{{ $coveragePeriod !== '' ? $coveragePeriod : '-' }}</td>
                                </tr>
                            </table>
                            {{-- <div id="policyPdfRender" class="check-policy-pdf-render" data-pdf-url="{{ asset('assets/files/sample.pdf') }}">
                                <p class="check-policy-pdf-loading">{{ __('content.check_policy.loading') }}</p>
                            </div> --}}
                        </div>
                        <p>
                            {{ __('content.check_policy.contact') }} 02-68-77777
                            {{ __('content.check_policy.or_email') }} <a href="mailto:info@sahainsurance.co.th">info@sahainsurance.co.th</a>
                        </p>
                    </div>
                    <div class="check-policy-actions">
                        <a href="{{ route('home') }}" class="otp-btn otp-btn-outline">{{ __('content.check_policy.home') }}</a>
                        <a href="{{ asset('assets/files/sample.pdf') }}" class="check-premium-submit receipt-policy-btn" download>{{ __('content.check_policy.download') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const container = document.getElementById('policyPdfRender');

        if (!container || !window.pdfjsLib) {
            return;
        }

        const pdfUrl = container.dataset.pdfUrl;
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        try {
            const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
            container.innerHTML = '';

            for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber += 1) {
                const page = await pdf.getPage(pageNumber);
                const baseViewport = page.getViewport({ scale: 1 });
                const availableWidth = container.clientWidth || baseViewport.width;
                const scale = availableWidth / baseViewport.width;
                const viewport = page.getViewport({ scale: scale });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                canvas.className = 'check-policy-pdf-page';
                canvas.width = Math.floor(viewport.width);
                canvas.height = Math.floor(viewport.height);
                canvas.style.width = viewport.width + 'px';
                canvas.style.height = viewport.height + 'px';
                container.appendChild(canvas);

                await page.render({
                    canvasContext: context,
                    viewport: viewport,
                }).promise;
            }
        } catch (error) {
            container.innerHTML = '<p class="check-policy-pdf-fallback">' + @json(__('content.check_policy.pdf_unavailable')) + ' <a href="' + pdfUrl + '" target="_blank" rel="noopener">' + @json(__('content.check_policy.open_pdf')) + '</a></p>';
        }
    });
</script>
@endpush