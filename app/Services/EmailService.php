<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use PHPMailer\PHPMailer\PHPMailer;

final class EmailService
{
    protected string $endpoint;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->endpoint = (string) config('services.zeptomail.endpoint', 'https://api.zeptomail.com/v1.1/email');
        $this->apiKey = config('services.zeptomail.api_key');
    }

    public function sendEmailSmtp(
        $email = null,
        $subject = null,
        $message = null
    ): bool {
        try {
            $mail = new PHPMailer();
            $mail->Encoding = 'base64';
            $mail->SMTPAuth = true;
            $mail->Host = (string) config('services.zeptomail.smtp_host', 'smtp.zeptomail.com');
            $mail->Port = (int) config('services.zeptomail.smtp_port', 587);
            $mail->Username = (string) config('services.zeptomail.smtp_username', 'emailapikey');
            $mail->Password = (string) config('services.zeptomail.api_key');
            $mail->SMTPSecure = (string) config('services.zeptomail.smtp_secure', 'tls');
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->From = (string) config('services.zeptomail.from_address', config('mail.from.address'));
            $mail->FromName = (string) config('services.zeptomail.from_name', config('mail.from.name'));
            $mail->addAddress($email);
            $mail->Body = $message;
            $mail->Subject = $subject;
            $mail->SMTPDebug = (int) config('services.zeptomail.smtp_debug', 0);
            $mail->Debugoutput = function ($str, $level): void {
                Log::debug("SMTP debug level {$level}; message: {$str}");
            };

            return $mail->send();
        } catch (\Throwable $error) {
            Log::error(
                "SMTP email sending failed: {$error->getMessage()} "
                . "in {$error->getFile()}:{$error->getLine()} "
                . "Trace: {$error->getTraceAsString()}"
            );

            return false;
        }
    }

    public function sendMailWithCurl(
        $toEmails = [],
        $subject = null,
        $message = null,
        $fromEmails = null
    ): bool {
        $toRecipients = $this->normalizeRecipients($toEmails);

        if (empty($toRecipients) || empty($subject) || $message === null || empty($this->apiKey)) {
            return false;
        }

        $dataMail = [
            'from' => $this->normalizeSender($fromEmails),
            'to' => $toRecipients,
            'subject' => $subject,
            'htmlbody' => $message,
        ];

        $post = json_encode($dataMail, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($post === false) {
            return false;
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                "authorization: Zoho-enczapikey {$this->apiKey}",
                'cache-control: no-cache',
                'content-type: application/json',
            ],
        ]);

        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        $curlErrNo = curl_errno($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErrNo !== 0) {
            Log::error("ZeptoMail curl error ({$curlErrNo}): {$curlError}");

            return false;
        }

        if ($httpCode < 200 || $httpCode >= 300 || empty($result)) {
            Log::error("ZeptoMail API request failed with status {$httpCode}: " . (string) $result);

            return false;
        }

        $response = json_decode($result, true);

        return isset($response['success']) ? (bool) $response['success'] : true;
    }

    private function normalizeRecipients($toEmails): array
    {
        if (is_string($toEmails)) {
            $toEmails = [$toEmails];
        }

        if (!is_array($toEmails)) {
            return [];
        }

        $recipients = [];

        foreach ($toEmails as $recipient) {
            if (is_string($recipient)) {
                $address = trim($recipient);

                if ($address === '') {
                    continue;
                }

                $recipients[] = [
                    'email_address' => [
                        'address' => $address,
                    ],
                ];

                continue;
            }

            if (!is_array($recipient)) {
                continue;
            }

            if (!empty($recipient['email_address']['address'])) {
                $emailAddress = [
                    'address' => trim((string) $recipient['email_address']['address']),
                ];

                $name = trim((string) ($recipient['email_address']['name'] ?? ''));

                if ($name !== '') {
                    $emailAddress['name'] = $name;
                }

                $recipients[] = [
                    'email_address' => $emailAddress,
                ];

                continue;
            }

            $address = trim((string) ($recipient['address'] ?? $recipient['email'] ?? ''));

            if ($address === '') {
                continue;
            }

            $emailAddress = ['address' => $address];
            $name = trim((string) ($recipient['name'] ?? ''));

            if ($name !== '') {
                $emailAddress['name'] = $name;
            }

            $recipients[] = [
                'email_address' => $emailAddress,
            ];
        }

        return $recipients;
    }

    private function normalizeSender($fromEmails): array
    {
        if (is_string($fromEmails)) {
            return ['address' => trim($fromEmails)];
        }

        if (!is_array($fromEmails)) {
            return [
                'address' => (string) config('services.zeptomail.from_address', config('mail.from.address')),
            ];
        }

        $address = trim((string) ($fromEmails['address'] ?? $fromEmails['email'] ?? config('services.zeptomail.from_address', config('mail.from.address'))));
        $sender = ['address' => $address];
        $name = trim((string) ($fromEmails['name'] ?? ''));

        if ($name !== '') {
            $sender['name'] = $name;
        }

        return $sender;
    }

    public function renderEmailTemplate(string $templatePath, array $data = []): string
    {
        if (!is_file($templatePath)) {
            throw new InvalidArgumentException('Email template not found: ' . $templatePath);
        }

        ob_start();

        try {
            extract($data, EXTR_SKIP);
            include $templatePath;

            return (string) ob_get_clean();
        } catch (\Throwable $error) {
            ob_end_clean();

            throw $error;
        }
    }
}
