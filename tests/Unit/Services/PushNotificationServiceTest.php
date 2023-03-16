<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use App\Services\PushNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PushNotificationServiceTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function it_constructors_sets_fcm_url_and_api_key_correctly_from_the_configuration_file()
    {
        // Prepare expected values from the configuration
        $expectedFcmUrl = config('services.firebase.url');
        $expectedApiKey = config('services.firebase.key');

        // Instantiate the PushNotificationService
        $pushNotificationService = new PushNotificationService(new Client());

        // Use Reflection to access private properties for testing
        $reflectedPushNotificationService = new \ReflectionClass(PushNotificationService::class);

        // Get the private property 'fcmUrl'
        $fcmUrlProperty = $reflectedPushNotificationService->getProperty('fcmUrl');
        $fcmUrlProperty->setAccessible(true);

        // Get the private property 'apiKey'
        $apiKeyProperty = $reflectedPushNotificationService->getProperty('apiKey');
        $apiKeyProperty->setAccessible(true);

        // Assert if the 'fcmUrl' property is set correctly
        $this->assertEquals($expectedFcmUrl, $fcmUrlProperty->getValue($pushNotificationService));

        // Assert if the 'apiKey' property is set correctly
        $this->assertEquals($expectedApiKey, $apiKeyProperty->getValue($pushNotificationService));
    }

    /** @test */
    public function it_send_push_notification_with_single_token()
    {
        // Mock the HTTP call to the FCM server
        $mockResponse = json_encode([
            'success' => 1,
            'failure' => 0,
            'results' => [
                [
                    'message_id' => 'test_message_id',
                ],
            ],
        ]);

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponse),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        // Pass the mocked client to the PushNotificationService constructor
        $pushNotificationService = new PushNotificationService($client);

        // Prepare test data
        $tokens = ['test_device_token'];
        $title = 'Test title';
        $body = 'Test body';

        // Call the sendPushNotification method
        $response = $pushNotificationService->sendPushNotification($tokens, $title, $body);

        // Assert if the response is as expected
        $this->assertEquals($mockResponse, $response);
    }

    /** @test */
    public function it_send_push_notification_with_multiple_tokens()
    {
        $mockResponse = json_encode([
            'success' => 1,
            'failure' => 0,
            'results' => [
                [
                    'message_id' => 'test_message_id',
                ],
            ],
        ]);

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponse),
        ]);
        $handlerStack = HandlerStack::create($mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $pushNotificationService = new PushNotificationService($client);

        $tokens = ['test_device_token_1', 'test_device_token_2'];
        $title = 'Test title';
        $body = 'Test body';

        $response = $pushNotificationService->sendPushNotification($tokens, $title, $body);

        $this->assertEquals($mockResponse, $response);
    }
}
