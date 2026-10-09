@php
    $brandName = trim((string) ($brandName ?? 'PA Direct'));
    $headerTitle = trim((string) ($headerTitle ?? 'Sending OTP Code'));
    $recipientName = trim((string) ($recipientName ?? 'Customer'));
    $introText = trim((string) ($introText ?? 'Please check the information below.'));
    $detailLabel = trim((string) ($detailLabel ?? 'Details'));
    $detailValue = trim((string) ($detailValue ?? ''));
    $secondaryLabel = trim((string) ($secondaryLabel ?? ''));
    $secondaryValue = trim((string) ($secondaryValue ?? ''));
    $bodyMessage = trim((string) ($bodyMessage ?? ''));
    $actionUrl = trim((string) ($actionUrl ?? ''));
    $actionLabel = trim((string) ($actionLabel ?? 'Open'));
    $actionHelpText = trim((string) ($actionHelpText ?? 'If the button does not work, please copy and paste this link.'));
    $footerText = trim((string) ($footerText ?? 'Ignore this email if you did not request an OTP code.'));
    $autoReplyText = trim((string) ($autoReplyText ?? 'Please do not reply to this email. This is an automated message.'));
@endphp
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
            }
        </style>
    </head>
    <body style="margin:0;padding:0;background:#f6f8fb;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f8fb;padding:22px 14px;">
            <tr>
                <td align="center">
                    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #e9eef5;border-radius:12px;max-width:600px;width:100%;overflow:hidden;">
                        <tr>
                            <td style="padding:18px 24px;background:#384c95;color:#ffffff;">
                                <div style="font-size:16px;font-weight:700;line-height:1.25;">
                                    {{ $headerTitle }}
                                </div>
                                <div style="font-size:12px;font-weight:600;line-height:1.4;margin-top:4px;opacity:.95;">
                                    {{ $brandName }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:24px;">
                                <p style="margin:0 0 14px 0;font-size:14px;color:#202124;line-height:1.6;">
                                    Dear {{ $recipientName }},
                                </p>

                                <p style="margin:0 0 14px 0;font-size:14px;color:#202124;line-height:1.6;">
                                    {{ $introText }}
                                </p>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5eaf2;border-radius:10px;">
                                    <tr>
                                        <td style="padding:14px 16px;">
                                            @if ($actionUrl !== '')
                                                <a href="{{ $actionUrl }}" style="display:block;text-decoration:none;color:inherit;">
                                            @endif

                                            <div style="font-size:12px;color:#5f6368;margin-bottom:6px;line-height:1.4;">
                                                {{ $detailLabel }}
                                            </div>

                                            @if ($detailValue !== '')
                                                <div style="font-size:14px;color:#202124;font-weight:700;line-height:1.5;">
                                                    {{ $detailValue }}
                                                </div>
                                            @endif

                                            @if ($secondaryLabel !== '' && $secondaryValue !== '')
                                                <div style="margin-top:6px;font-size:12px;color:#5f6368;line-height:1.5;">
                                                    {{ $secondaryLabel }}: <span style="color:#0b57d0;">{{ $secondaryValue }}</span>
                                                </div>
                                            @endif

                                            @if ($actionUrl !== '')
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                                @if ($bodyMessage !== '')
                                    <p style="margin:14px 0 0 0;font-size:14px;color:#202124;line-height:1.6;">
                                        {!! nl2br(e($bodyMessage)) !!}
                                    </p>
                                @endif

                                @if ($actionUrl !== '')
                                    <div style="margin:22px 0 10px 0;text-align:center;">
                                        <a href="{{ $actionUrl }}" style="display:inline-block;background:#0b57d0;color:#ffffff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:700;font-size:14px;">
                                            {{ $actionLabel }}
                                        </a>
                                    </div>

                                    <p style="margin:0;text-align:center;font-size:12px;color:#5f6368;line-height:1.5;">
                                        {{ $actionHelpText }}<br>
                                        <span style="color:#0b57d0;word-break:break-all;">{{ $actionUrl }}</span>
                                    </p>
                                @endif

                                <hr style="border:none;border-top:1px solid #eef2f7;margin:22px 0;">

                                <p style="margin:0;font-size:12px;color:#5f6368;line-height:1.6;">
                                    {{ $footerText }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:14px 24px;background:#fafbff;border-top:1px solid #eef2f7;">
                                <div style="font-size:12px;color:#5f6368;line-height:1.4;">
                                    &copy; {{ date('Y') }} {{ $brandName }}
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div style="width:600px;max-width:100%;margin-top:10px;font-size:11px;color:#9aa0a6;text-align:center;line-height:1.5;">
                        {{ $autoReplyText }}
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>
