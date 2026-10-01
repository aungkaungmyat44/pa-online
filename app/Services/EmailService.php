<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Http;

final class EmailService
{
    protected string $endpoint;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->endpoint = (string) config('services.zeptomail.endpoint', 'https://api.zeptomail.com/v1.1/email');
        $this->apiKey = config('services.zeptomail.api_key');
    }

    public function sendEmailApi(
    string $email,
    string $subject,
    string $message,
    ): bool {
        try {
            if (blank($this->apiKey)) {
                throw new InvalidArgumentException('ZeptoMail API key is missing.');
            }

            $response = Http::acceptJson()
                ->asJson()
                ->withHeaders([
                    'Authorization' => 'Zoho-enczapikey ' . $this->apiKey,
                ])
                ->connectTimeout(10)
                ->timeout(30)
                ->post($this->endpoint, [
                    'from' => [
                        'address' => config(
                            'services.zeptomail.from_address',
                            config('mail.from.address'),
                        ),
                        'name' => config(
                            'services.zeptomail.from_name',
                            config('mail.from.name'),
                        ),
                    ],
                    'to' => [
                        [
                            'email_address' => [
                                'address' => $email,
                            ],
                        ],
                    ],
                    'subject' => $subject,
                    'htmlbody' => $message,
                ]);

            if ($response->failed()) {
                Log::error('ZeptoMail API rejected the email.', [
                    'status' => $response->status(),
                    'code' => $response->json('error.code'),
                ]);

                return false;
            }
            Log::info('ZeptoMail API email sent successfully.', [
                'status' => $response->status(),
                'code' => $response->json('code'),
            ]);
            return $response->successful();
        } catch (\Throwable $error) {
            Log::error('ZeptoMail API email sending failed.', [
                'exception' => get_class($error),
            ]);

            return false;
        }
    }
}
