<?php

namespace App\Services;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class Twilio implements SMSClientInterface
{
    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function send(string $phoneNumber, string $message): array
    {
        $account_sid = 'ACaf9e9f097a54480b4b37c09304640f03';
        $auth_token = '5b1e1d9feb751172808d850605e8d136';

        $twilio_number = "+15735494379";

        $client = new Client($account_sid, $auth_token);

        $response = $client->messages->create(
            $phoneNumber,
            [
                'from' => $twilio_number,
                'body' => $message
            ]
        );

        return $response->toArray();
    }
}
