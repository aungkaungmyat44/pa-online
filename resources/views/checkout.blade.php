@extends('layout.master')

@section('title', 'ดำเนินการชำระเงิน')

@section('content')
@php
    $checkoutData = $checkoutData['data'];
    $orderInfo = $order['order_info'];
    $orderId = $order->id;
    $orderIdValue = $order->order_unique_code;
    $productId = $orderInfo['plan']['id'];
    $productName = $orderInfo['plan']['name_th'];
    $totalAmount = $order->total_amount;
    $paymentMethod = $order->payment_method;
    
    $qrOrderId = $checkoutData['qrId'] ?? "";
    $linkUrl = $checkoutData['linkUrl'] ?? "";
    $linkQrCode = $checkoutData['qrCodeSrc'] ?? "";

    $isLinkPayment = $linkUrl !== '' or $linkQrCode !== '';
    $isQrPayment = !empty($qrOrderId) == true;
    $isCardPayment = !$isLinkPayment and !$isQrPayment;
    $orderCreatedAtLabel = date('d M Y, H:i', strtotime($order->created_at));
@endphp

<section class="payment-inquiry-shell py-5 d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at top, rgba(56,76,149,.1), transparent 60%);">
    <div class="mt-3 card" style="max-width: 500px; width: 100%; border-radius: 28px; border: 1px solid rgba(56, 76, 149, 0.18); box-shadow: 0 20px 45px rgba(26, 38, 91, 0.12); background: linear-gradient(180deg, rgba(255,255,255,0.9), #ffffff);">
        <div class="card-body text-center d-flex flex-column gap-3" style="padding: 2.5rem 1.75rem;">
            <div class="d-flex justify-content-center">
                <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
                <dotlottie-player
                    src="{{ asset('assets/animations/animation.lottie') }}"
                    background="transparent"
                    speed="1"
                    style="width: 180px; height: 180px;"
                    loop
                    autoplay>
                </dotlottie-player>
            </div>

            <p class="fw-semibold mb-0" style="font-size: var(--font-size-p); color: #2f3c63;">
                กรุณาชำระเงินภายใน <span class="text-danger fw-bold">3 นาที</span> ระบบจะอัปเดตสถานะการชำระเงินโดยอัตโนมัติครับ
            </p>

            <div class="text-start w-100" style="font-size: var(--font-size-p);">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>สินค้า</span>
                    <span class="text-dark fw-semibold text-truncate" style="max-width: 140px; display: inline-block; text-align: right;">
                        {{ $productName }}
                    </span>
                </div>
                @if ($orderIdValue !== '')
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>เลขที่อ้างอิงคำสั่งซื้อ</span>
                        <span class="text-dark fw-semibold text-break" style="max-width: 200px; text-align: right;">
                            # {{ $orderIdValue }}
                        </span>
                    </div>
                @endif
                @if ($orderCreatedAtLabel !== '')
                    <div class="d-flex justify-content-between small text-muted">
                        <span>วันที่ทำรายการ</span>
                        <span class="text-dark fw-semibold" style="text-align: right;">
                            {{ $orderCreatedAtLabel }}
                        </span>
                    </div>
                @endif
            </div>

            @if ($isCardPayment)
                <div class="row mt-3 justify-content-center">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <form method="POST" action="{{ route('kbank-checkout', $order) }}" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="product_id" id="checkout_product_id" value="{{ $productId }}">
                            <input type="hidden" name="product_name" id="checkout_product_name" value="{{ $productName }}">
                            <input type="hidden" name="product_price" id="checkout_product_price" value="{{ $totalAmount }}">
                            <input type="hidden" name="order_id" value="{{ $orderIdValue }}">
                            <script type="text/javascript" id="kpay-script"
                                src="{{ $kbankJsFileLink }}"
                                data-apikey="{{ $kbankPublicKey }}"
                                data-amount="{{ $totalAmount }}"
                                data-currency="THB"
                                data-payment-methods="card"
                                data-name="{{ $kbankMerchantName }}"
                                data-mid="{{ $kbankMasterMerchantId }}">
                            </script>
                        </form>
                    </div>
                </div>
            @endif

            @if ($isQrPayment and $qrOrderId !== '')
                <div class="row mt-3 justify-content-center">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        <form method="POST" action="{{ route('show-checkout') }}" id="qrCheckoutForm">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $orderIdValue }}">
                            <script type="text/javascript"
                                src="{{ $kbankJsFileLink }}"
                                data-apikey="{{ $kbankPublicKey }}"
                                data-amount="{{ $totalAmount }}"
                                data-currency="THB"
                                data-payment-methods="qr"
                                data-name="{{ $kbankMerchantName }}"
                                data-order-id="{{ $qrOrderId }}">
                            </script>
                        </form>
                    </div>
                </div>
            @endif

            @if ($isLinkPayment)
                @if ($linkQrCode !== '')
                    <div class="mt-3">
                        <img
                            src="{{ $linkQrCode }}"
                            alt="Payment QR Code"
                            style="width: 220px; max-width: 100%; height: auto; border-radius: 18px; border: 1px solid rgba(56, 76, 149, 0.16); background: #fff; padding: 0.65rem; box-shadow: 0 10px 24px rgba(26, 38, 91, 0.08);"
                        >
                    </div>
                @endif

                <div class="row mt-3 justify-content-center">
                    <div class="col-xl-12 col-md-12 col-sm-12">
                        @if ($linkUrl !== '')
                            <a
                                href="{{ $linkUrl }}"
                                id="linkPaymentBtn"
                                class="defaultBtn successBtn btn w-100"
                                target="_blank"
                                rel="noopener"
                            >
                                <i class="bi bi-credit-card me-1"></i>Open payment link
                            </a>
                            <button
                                type="button"
                                id="copyLinkBtn"
                                class="defaultBtn secondaryBtn btn w-100 mt-2"
                                data-link-url="{{ $linkUrl }}"
                            >
                                <i class="bi bi-copy me-1"></i>Copy payment link
                            </button>
                        @else
                            <button type="button" class="defaultBtn successBtn btn w-100" disabled>
                                <i class="bi bi-credit-card me-1"></i>Payment link unavailable
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        const IS_CARD_PAYMENT = @json($isCardPayment);
        const IS_QR_PAYMENT = @json($isQrPayment);
        const IS_LINK_PAYMENT = @json($isLinkPayment);

        let RECEIPT_URL = "{{ route('receipt', $order) }}";
        let PAYMENT_ISSUE_URL = "{{ route('payment-issue', $order) }}";
        let INQUIRE_URL = "{{ route('kbank-payment-inquiry', $order) }}";
        let HOME_PAGE_URL = "{{ route('home') }}";

        async function getPayment() {
            let fullUrl = INQUIRE_URL;
            let data = {
                'order_id': @json($orderIdValue)
            };
            console.log(data);
            return await getData(fullUrl, data);
        };

        const PAYMENT_POLL_INTERVAL_MS = 60000;
        const PAYMENT_REDIRECT_DELAY_MS = 300000;

        const GENERIC_PAYMENT_ERROR = "Please try again later.";
        const AUTO_START_FORM_IDS = [];
        const AUTO_START_LINK_IDS = [];
        let paymentPollTimer = null;
        let paymentErrorHandled = false;

        if (IS_CARD_PAYMENT) {
            AUTO_START_FORM_IDS.push('checkoutForm');
        }

        if (IS_QR_PAYMENT) {
            AUTO_START_FORM_IDS.push('qrCheckoutForm');
        }

        if (IS_LINK_PAYMENT && @json($linkUrl !== '')) {
            AUTO_START_LINK_IDS.push('linkPaymentBtn');
        }

        function tryAutoStartPayment(formId) {
            const checkoutForm = document.getElementById(formId);
            if (!checkoutForm || checkoutForm.dataset.autoStarted === '1') {
                return false;
            }

            const trigger = checkoutForm.querySelector('button:not([disabled]), input[type="submit"]:not([disabled]), input[type="button"]:not([disabled])');
            if (!trigger) {
                return false;
            }

            checkoutForm.dataset.autoStarted = '1';
            trigger.click();
            return true;
        }

        function tryAutoOpenLink(linkId) {
            const paymentLink = document.getElementById(linkId);
            if (!paymentLink || paymentLink.dataset.autoStarted === '1') {
                return false;
            }

            paymentLink.dataset.autoStarted = '1';
            window.open(paymentLink.href, paymentLink.target || '_blank', 'noopener');
            return true;
        }

        async function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return true;
            }

            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.setAttribute('readonly', 'readonly');
            textArea.style.position = 'absolute';
            textArea.style.left = '-9999px';
            document.body.appendChild(textArea);
            textArea.select();
            const copied = document.execCommand('copy');
            document.body.removeChild(textArea);

            return copied;
        }

        function getPaymentErrorMessage(error) {
            const response = error?.response;
            if (response && typeof response === 'object') {
                return response.message || response.error || GENERIC_PAYMENT_ERROR;
            }

            if (typeof response === 'string' && response.trim() !== '') {
                try {
                    const parsedResponse = JSON.parse(response);
                    if (parsedResponse && typeof parsedResponse === 'object') {
                        return parsedResponse.message || parsedResponse.error || GENERIC_PAYMENT_ERROR;
                    }
                } catch (parseError) {
                    return response;
                }
            }

            return error?.error || GENERIC_PAYMENT_ERROR;
        }

        function handlePaymentError(error) {
            if (paymentErrorHandled) {
                return;
            }

            paymentErrorHandled = true;
            if (paymentPollTimer !== null) {
                clearInterval(paymentPollTimer);
                paymentPollTimer = null;
            }
            const message = getPaymentErrorMessage(error);
            window.location.href = PAYMENT_ISSUE_URL + '&message=' + encodeURIComponent(message);
        }

        async function pollPaymentTransition() {
            try {
                const response = await getPayment();
                const payment = response?.data;
                const status = typeof payment?.status === 'string' ? payment.status.toLowerCase() : '';

                if (status && ['success', 'fail'].includes(status) && paymentPollTimer !== null) {
                    clearInterval(paymentPollTimer);
                    paymentPollTimer = null;
                    window.location.href = RECEIPT_URL;
                }
            } catch (error) {
                console.error('Polling payment transition failed', error);
                handlePaymentError(error);
            }
        }

        function scheduleRedirect() {
            window.location.href = HOME_PAGE_URL;
        }

        $(document).ready(function() {
            const copyLinkBtn = document.getElementById('copyLinkBtn');
            if (copyLinkBtn) {
                copyLinkBtn.addEventListener('click', async function () {
                    const originalText = copyLinkBtn.innerHTML;
                    const linkUrl = copyLinkBtn.dataset.linkUrl || '';
                    if (!linkUrl) {
                        return;
                    }

                    try {
                        await copyText(linkUrl);
                        copyLinkBtn.innerHTML = '<i class="bi bi-check2 me-1"></i>Copied';
                    } catch (error) {
                        copyLinkBtn.innerHTML = '<i class="bi bi-x-circle me-1"></i>Copy failed';
                    }

                    setTimeout(function () {
                        copyLinkBtn.innerHTML = originalText;
                    }, 1800);
                });
            }

            if (AUTO_START_FORM_IDS.length > 0 || AUTO_START_LINK_IDS.length > 0) {
                let autoStartAttempts = 0;
                const autoStartTimer = setInterval(function () {
                    autoStartAttempts += 1;
                    const startedForm = AUTO_START_FORM_IDS.some(function (formId) {
                        return tryAutoStartPayment(formId);
                    });
                    const startedLink = AUTO_START_LINK_IDS.some(function (linkId) {
                        return tryAutoOpenLink(linkId);
                    });

                    if (startedForm || startedLink || autoStartAttempts >= 20) {
                        clearInterval(autoStartTimer);
                    }
                }, 500);
            }

            pollPaymentTransition();
            paymentPollTimer = setInterval(pollPaymentTransition, PAYMENT_POLL_INTERVAL_MS);

            setTimeout(scheduleRedirect, PAYMENT_REDIRECT_DELAY_MS);
        });
    </script>
</section>
@endsection
