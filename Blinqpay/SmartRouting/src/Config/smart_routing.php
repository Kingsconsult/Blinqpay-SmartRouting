<?php

return [
    'processors' => [
        [
            'name' => 'Processor1',
            'cost' => 2.5,
            'reliability' => 90,
            'currency_support' => ['USD', 'EUR'],
            'country_support' => ['US', 'CA'],
            'status' => true
        ],
        [
            'name' => 'Processor2',
            'cost' => 1.8,
            'reliability' => 85,
            'currency_support' => ['USD', 'NGN'],
            'country_support' => ['US', 'NG'],
            'status' => true
        ]
    ],
    'routing_criteria' => [
        'currency' => 'USD',
        'country' => 'US',
        'max_cost' => 2.0,
        'min_reliability' => 80
    ],
];
