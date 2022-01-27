<?php
/**
 * Local configuration
 */
return [
    'rabbitmq' => [
        'url' => 'localhost',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest',
        'queue_name' => 'jobs',
    ],
    'mailer' => [
        'from_email' => 'test@mandrillapp.com',
        'mailchimp' => [
            'api_key' => 'KlbUQrcXxAk5NhAlNk2zdQ'
        ]
    ]
];