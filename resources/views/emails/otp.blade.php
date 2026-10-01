@php
    $brandName = trim((string) ($brandName ?? 'PA Online'));
    $headerTitle = trim((string) ($headerTitle ?? "Sending OTP Code"));
    $recipientName = trim((string) ($recipientName ?? "Customer"));
    $introText = trim((string) ($introText ?? "Please check the information below"));
    $detailLabel = trim((string) ($detailLabel ?? "Details"));
    $detailValue = trim((string) ($detailValue ?? ''));
    $secondaryLabel = trim((string) ($secondaryLabel ?? ''));
    $secondaryValue = trim((string) ($secondaryValue ?? ''));
    $bodyMessage = trim((string) ($bodyMessage ?? ''));
    $actionUrl = trim((string) ($actionUrl ?? ''));
    $actionLabel = trim((string) ($actionLabel ?? "Open"));
    $actionHelpText = trim((string) ($actionHelpText ?? "If the button does not work, please copy and paste this link."));
    $footerText = trim((string) ($footerText ?? "Ignore this email if you did not request an OTP code."));
    $autoReplyText = trim((string) ($autoReplyText ?? "Please do not reply to this email. This is an automated message."));
@endphp

<x-mail::message>
# {{ $headerTitle }}

**{{ $brandName }}**

Dear {{ $recipientName }},

{{ $introText }}

<x-mail::panel>
{{ $detailLabel }}

@if ($detailValue !== '')
<strong>{{ $detailValue }}</strong>
@endif

@if ($secondaryLabel !== '' && $secondaryValue !== '')
<br>{{ $secondaryLabel }}: {{ $secondaryValue }}
@endif
</x-mail::panel>

@if ($bodyMessage !== '')
{!! nl2br(e($bodyMessage)) !!}
@endif

@if ($actionUrl !== '')
<x-mail::button :url="$actionUrl">
{{ $actionLabel }}
</x-mail::button>

<x-mail::subcopy>
{{ $actionHelpText }}

<a href="{{ $actionUrl }}" style="word-break: break-all;">{{ $actionUrl }}</a>
</x-mail::subcopy>
@endif

---

{{ $footerText }}

{{ $autoReplyText }}

© {{ date('Y') }} {{ $brandName }}
</x-mail::message>