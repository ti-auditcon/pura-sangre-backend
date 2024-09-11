<?php

namespace App\Services;

use GuzzleHttp\Client;
use Google\Auth\CredentialsLoader;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $credentialsPath;
    protected $httpClient;
    protected $url;

    public function __construct()
    {
        $this->url = config('services.firebase.url');
        $this->credentialsPath = base_path(config('services.google_application_credentials'));
        $this->httpClient = $this->authorizeHttpClient();
    }

    public function authorizeHttpClient()
    {
        $creds = CredentialsLoader::makeCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            json_decode(file_get_contents($this->credentialsPath), true)
        );

        // Fetch the token to check if it's working
        $token = $creds->fetchAuthToken();
        $accessToken = $token['access_token'];

        return new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Send push notification to multiple devices
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @return void
     */
    public function sendPushNotification(array $tokens, string $title, string $body)
    {
        foreach ($tokens as $token) {
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => [
                        'title' => $title,
                        'body' => $body,
                    ]
                ],
            ];

            try {
                $this->httpClient->post($this->url, [
                    'json' => $payload,
                ]);
            } catch (\Exception $e) {
                Log::error('Error sending push notification: ' . $e->getMessage());
            }
        }
    }
}
