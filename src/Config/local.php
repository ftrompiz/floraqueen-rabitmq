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
        'from_email' => 'francisco.trompizbergueiro@amaris.com',
        'php_email' => [

        ],
        'mailchimp' => [
            'api_key' => '123456789'
        ]
    ]
];