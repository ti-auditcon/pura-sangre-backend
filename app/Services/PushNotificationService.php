<?php

namespace App\Services;

use GuzzleHttp\Client;

class PushNotificationService
{
	/**
	 * The FCM API url to send the push notification.
	 *
	 * @var string
	 */
    private $fcmUrl;

	/**
	 * The FCM API key.
	 * 
	 * @var string
	 */
    private $apiKey;

    /**
     * The Guzzle HTTP client.
     *
     * @var Client
     */
    private $client;

    public function __construct(Client $client = null)
    {
        $this->fcmUrl = config('services.firebase.url');
        $this->apiKey = config('services.firebase.key');

        $this->client = $client ?? new Client();
    }

    public function sendPushNotification(array $tokens, $title, $body)
    {
        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => true,
        ];

        $data = [
            'message' => $notification,
        ];

        // Set the appropriate field based on the number of tokens
        if (count($tokens) === 1) {
            $fields = [
                'to' => $tokens[0],
                'notification' => $notification,
                'data' => $data,
            ];
        } else {
            $fields = [
                'registration_ids' => $tokens,
                'notification' => $notification,
                'data' => $data,
            ];
        }

        $headers = [
            'Authorization' => 'key=' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $response = $this->client->post(
            $this->fcmUrl,
            [
                'headers' => $headers,
                'json' => $fields
            ]
        );

        return $response->getBody()->getContents();
    }
}
