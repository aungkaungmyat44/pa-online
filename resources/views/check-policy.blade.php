@extends('layout.master')

@section('title', 'Check Policy')

@section('content')
@php
    $policyNumber = 'MISC-PAI26-0417-09031';
    $orderReference = '#00001';
    $customerName = 'Mr. William';
    $productName = 'Personal Accident Insurance';
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
                        <p>Sahamongkhon Public Company Limited</p>
                    </div>
                    <div class="check-policy-title-bar">
                        <span>Electronic Policy (E-Policy)</span>
                        <strong>Policy No. {{ $policyNumber }}</strong>
                    </div>
                    <div class="check-policy-body">
                        <div class="check-policy-table-wrap">
                            <div id="policyPdfRender" class="check-policy-pdf-render" data-pdf-url="{{ asset('assets/files/sample.pdf') }}">
                                <p class="check-policy-pdf-loading">Loading policy...</p>
                            </div>
                        </div>
                        <p>
                            If you have any questions, please contact Customer Service at 02-68-77777
                            or email <a href="mailto:example@gmail.com">example@gmail.com</a>.
                        </p>
                    </div>
                    <div class="check-policy-actions">
                        <a href="{{ route('home') }}" class="otp-btn otp-btn-outline">Home</a>
                        <a href="{{ asset('assets/files/sample.pdf') }}" class="check-premium-submit receipt-policy-btn" download>Download Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
            container.innerHTML = '<p class="check-policy-pdf-fallback">Unable to render policy PDF. <a href="' + pdfUrl + '" target="_blank" rel="noopener">Open Policy PDF</a></p>';
        }
    });
</script>
@endsection
