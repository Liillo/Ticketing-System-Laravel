<?php

namespace App\Services;

class MpesaService
{
    private $shortCode;
    private $passKey;
    private $callbackUrl;
    private $env;
    private $consumerKey;
    private $consumerSecret;

    public function __construct()
    {
        $this->shortCode = env('MPESA_SHORTCODE', '600988');
        $this->passKey = env('MPESA_PASSKEY', 'YOUR_PASSKEY');
        $this->callbackUrl = env('MPESA_CALLBACK', 'https://yourdomain.com/callback');
        $this->env = env('MPESA_ENV', 'sandbox'); // 'live' or 'sandbox'
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
    }

    // Step 1: Get access token
    private function getAccessToken()
    {
        $url = $this->env === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->consumerKey . ':' . $this->consumerSecret);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        return $data['access_token'] ?? null;
    }

    // Step 2: STK Push
    public function stkPush($phone, $amount = 1, $accountRef = 'TestPayment', $description = 'Payment')
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return ['error' => 'Failed to get access token'];
        }

        $url = $this->env === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortCode . $this->passKey . $timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->shortCode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => $accountRef,
            'TransactionDesc' => $description,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => $error_msg];
        }

        curl_close($ch);

        // Decode JSON manually
        $data = json_decode($response, true);
        return $data;
    }
}
