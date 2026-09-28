<?php

namespace App\Services;

use Exception;

final class HttpService
{
    public $endPoint;
    public $headers;
    public $fields;
    public $method;

    public function __construct($endPoint, $headers, $fields, $method)
    {
        $this->endPoint = $endPoint;
        $this->headers = $headers;
        $this->fields = $fields;
        $this->method = $method;
    }

    public function send(): array
    {
        $ch = curl_init($this->endPoint);
        $isPostMethod = strtolower($this->method) === 'post';

        curl_setopt_array($ch, [
            CURLOPT_POST => $isPostMethod,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_POSTFIELDS => $this->fields,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        return $this->executeCurl($ch);
    }

    public function sendXml(string $xml): array
    {
        $ch = curl_init($this->endPoint);
        $isPostMethod = strtolower($this->method) === 'post';

        curl_setopt_array($ch, [
            CURLOPT_POST => $isPostMethod,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $xml,
            CURLOPT_HTTPHEADER => [
                'Content-Type: text/xml; charset=utf-8',
                'Content-Length: ' . strlen($xml),
                'SOAPAction: "ServiceCPL_JSON"',
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        $xml = simplexml_load_string($response);

        $servicePolicyUrl = SERVICE_POLICY_URL;
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns1', $servicePolicyUrl);

        $result = $xml->xpath('//ns1:ServiceCPL_JSONResponse/ServiceCPL_JSONResult')[0];

        return [
            'result' => (string) $result->Result ?? '',
            'PolicyNo' => (string) $result->PolicyNo ?? '',
            'Barcode' => (string) $result->Barcode ?? '',
            'PolicyURL' => (string) $result->PolicyURL ?? '',
            'errorCode' => (string) $result->errorCode ?? '',
            'errorMessage' => (string) $result->errorMessage ?? '',
            'premium' => (string) $result->premium ?? '',
            'vat' => (string) $result->vat ?? '',
            'duty' => (string) $result->duty ?? '',
            'total' => (string) $result->total ?? '',
            'p_code' => (string) $result->p_code ?? '',
        ];
    }

    public function sendGet(array $params = []): array
    {
        $url = $this->endPoint;

        if (!empty($params)) {
            $querySeparator = strpos($url, '?') === false ? '?' : '&';
            $url .= $querySeparator . http_build_query($params);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        return $this->executeCurl($ch);
    }

    private function executeCurl($ch): array
    {
        $rawResponse = curl_exec($ch);
        $curlErrNo = curl_errno($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErrNo !== 0) {
            throw new Exception('Fail to proceed the request: ' . $curlErr);
        }

        $decoded = json_decode((string) $rawResponse, true);

        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}
