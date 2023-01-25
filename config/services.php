<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'firebase' => [
        'url' => env('FIREBASE_CLOUD_MESSAGING_URL', 'https://fcm.googleapis.com/fcm/send'),
        'key' => env(
            'FIREBASE_AUTHORIZATION_KEY',
            'AAAAUs-oQYg:APA91bGiLo6q8N33eRFGqSDpoDIeu-ZmFRU7ZDpeHFMcy1yHHPrP3Bdrh3OyZNJUNMJOw9oSXhSgjVwCrVBnya4u0coSefqqXHk7QT0L8LdioSTxAUbek4tNhbzuWsUMNiyS2PlGJFbZ'
        ),
    ],
];
