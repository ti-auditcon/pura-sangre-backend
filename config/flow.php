<?php

return [
    
    'sandbox' => [
        'apiKey' => env('SANDBOX_FLOW_API_KEY', '2BF50213-7407-4BDE-9EEA-461FL347859C'),
        'secret' => env('SANDBOX_FLOW_SECRET', '44416dd27ebc608516a7190d6b6690ab1ec44138'),
    ],

    'production' => [
        'apiKey' => env('FLOW_API_KEY'),
        'secret' => env('FLOW_SECRET'),
    ]
    
];

// $this->flow   = Flow::make('production', [
//     'apiKey'    => '25F26959-CA01-4899-9216-8918538CL80F',
//     'secret'    => 'a8c71eabb93099922ae0aec12acd62d606c1ca3e',
// ]);