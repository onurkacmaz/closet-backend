<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Exception;
use GuzzleHttp\Client;

class VatanSms implements SMSClientInterface
{
    private string $url = 'http://panel.vatansms.com/panel/smsgonder1Npost.php';
    private string $customerNumber = 'DEMO-247237';
    private string $username = 'onurkacmaz';
    private string $password = 'vandeta55';
    private string|null $headText = "SMS Verification";
    private string $type = "Normal";
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function send(string $phoneNumber, string $message): array
    {
        $body = sprintf("
            data=<sms>
            <kno>%s</kno>
            <kulad>%s</kulad>
            <sifre>%s</sifre>
            <gonderen>%s</gonderen>
            <mesaj>%s</mesaj>
            <numaralar>%s</numaralar>
            <tur>%s</tur>
            </sms>
        ", $this->customerNumber, $this->username, $this->password, $this->headText, $message, $phoneNumber, $this->type);
        try {
            $response = $this->client->request("POST", $this->url, [
                'headers' => [
                    'Content-Type' => 'text/plain'
                ],
                'body' => $body
            ]);
            dd($response->getBody()->getContents());

            return json_decode($response->getBody(), true);
        }catch (Exception $e) {
            throw new ApiException("SMS_SENDING_FAILED", 400);
        }
    }
}
