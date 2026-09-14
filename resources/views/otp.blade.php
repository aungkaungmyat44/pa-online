@extends('layout.master')

@section('title', 'OTP Verification')

@section('content')
@php
    $customer = $customer ?? [];
    $email = trim((string) ($customer['email'] ?? ''));
    $occupation = trim((string) ($customer['occupation'] ?? ''));
    $dateOfBirth = trim((string) ($customer['date_of_birth'] ?? ''));
@endphp

<section id="otp-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ol class="mi-progress mi-progress-six" aria-label="Check premium progress">
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <span class="mi-progress-label">Inquire</span>
                    </li>
                    <li class="mi-progress-step is-active">
                        <span class="mi-progress-marker">
                            <i class="bi bi-mailbox-flag"></i>
                        </span>
                        <span class="mi-progress-label">OTP Verification</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-ui-checks-grid"></i>
                        </span>
                        <span class="mi-progress-label">Questionnaire</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        <span class="mi-progress-label">Personal Information</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-file-earmark-check"></i>
                        </span>
                        <span class="mi-progress-label">Review</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-credit-card"></i>
                        </span>
                        <span class="mi-progress-label">Payment</span>
                    </li>
                </ol>
            </div>
        </div>

        <div class="otp-shell">
            <h1>Verify OTP</h1>
            <p>Enter the 6-digit OTP code sent to your email address to continue.</p>

            <div class="otp-email">
                <i class="bi bi-envelope-at me-1"></i>
                {{ $email !== '' ? $email : '-' }}
            </div>

            <form id="otpForm" action="{{ route('health-questions') }}" method="POST" autocomplete="one-time-code">
                @csrf
                <input type="hidden" name="occupation" value="{{ $occupation }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="date_of_birth" value="{{ $dateOfBirth }}">
                <input type="hidden" name="otp_code" id="otp_code" value="">

                <div class="otp-inputs">
                    <input class="form-control otp-input" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*" aria-label="OTP digit 1">
                    <input class="form-control otp-input" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*" aria-label="OTP digit 2">
                    <input class="form-control otp-input" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*" aria-label="OTP digit 3">
                    <input class="form-control otp-input" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*" aria-label="OTP digit 4">
                    <input class="form-control otp-input" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*" aria-label="OTP digit 5">
                    <input class="form-control otp-input" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]*" aria-label="OTP digit 6">
                </div>

                <div class="otp-actions">
                    <button type="submit" id="otpSubmitBtn" class="otp-btn otp-btn-primary" disabled>Verify OTP</button>
                    <button type="button" id="otpResendBtn" class="otp-btn otp-btn-secondary">Resend OTP</button>
                    <a href="{{ route('check-premium') }}" class="otp-btn otp-btn-outline">Back</a>
                </div>

                <p class="otp-note">If you did not receive the OTP, please check your Spam or Junk folder.</p>
            </form>
        </div>
    </div>
</section>

<script>
    (function () {
        const form = document.getElementById('otpForm');
        if (!form) return;

        const inputs = Array.from(form.querySelectorAll('.otp-input'));
        const hiddenOtp = document.getElementById('otp_code');
        const submitBtn = document.getElementById('otpSubmitBtn');
        const resendBtn = document.getElementById('otpResendBtn');
        const email = form.querySelector('input[name="email"]').value.trim();
        const countdownStorageKey = 'paOtpResendCountdown:' + email;
        const resendLabel = resendBtn.textContent.trim();

        function updateOtpState() {
            const otp = inputs.map((input) => input.value.trim()).join('');
            hiddenOtp.value = otp;
            submitBtn.disabled = otp.length !== 6 || /[^0-9]/.test(otp);
        }

        function clearCountdown() {
            window.localStorage.removeItem(countdownStorageKey);
        }

        function resetResendButton() {
            const timerId = resendBtn.dataset.timerId;
            if (timerId) {
                window.clearInterval(Number(timerId));
            }

            delete resendBtn.dataset.timerId;
            resendBtn.disabled = false;
            resendBtn.textContent = resendLabel;
        }

        function renderCountdown(expiryTimestamp) {
            const remainingSeconds = Math.max(0, Math.ceil((expiryTimestamp - Date.now()) / 1000));

            if (remainingSeconds <= 0) {
                clearCountdown();
                resetResendButton();
                return false;
            }

            resendBtn.disabled = true;
            resendBtn.textContent = remainingSeconds + 's';
            return true;
        }

        function runCountdown(expiryTimestamp) {
            const existingTimerId = resendBtn.dataset.timerId;
            if (existingTimerId) {
                window.clearInterval(Number(existingTimerId));
            }

            if (!renderCountdown(expiryTimestamp)) return;

            const timerId = window.setInterval(function () {
                renderCountdown(expiryTimestamp);
            }, 1000);

            resendBtn.dataset.timerId = String(timerId);
        }

        inputs.forEach(function (input, index) {
            input.addEventListener('input', function () {
                input.value = (input.value || '').replace(/\D/g, '').slice(0, 1);
                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateOtpState();
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }

                if (event.key === 'ArrowLeft' && index > 0) {
                    event.preventDefault();
                    inputs[index - 1].focus();
                }

                if (event.key === 'ArrowRight' && index < inputs.length - 1) {
                    event.preventDefault();
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', function (event) {
                const pasted = (event.clipboardData || window.clipboardData).getData('text') || '';
                const digits = pasted.replace(/\D/g, '').slice(0, 6).split('');
                if (!digits.length) return;

                event.preventDefault();
                inputs.forEach(function (otpInput, inputIndex) {
                    otpInput.value = digits[inputIndex] || '';
                });

                inputs[Math.min(digits.length, 6) - 1].focus();
                updateOtpState();
            });
        });

        resendBtn.addEventListener('click', function () {
            if (resendBtn.disabled) return;

            const expiryTimestamp = Date.now() + 60000;
            window.localStorage.setItem(countdownStorageKey, String(expiryTimestamp));
            runCountdown(expiryTimestamp);
        });

        form.addEventListener('submit', function (event) {
            updateOtpState();
            if (submitBtn.disabled) {
                event.preventDefault();
            }
        });

        const storedExpiry = parseInt(window.localStorage.getItem(countdownStorageKey), 10) || 0;
        if (storedExpiry > Date.now()) {
            runCountdown(storedExpiry);
        } else {
            clearCountdown();
        }

        updateOtpState();
        inputs[0].focus();
    })();
</script>
@endsection
