# Floraqueen-rabitmq

Test Rabitmq for Floraqueen 

## First step, composer

Initialize the proyecct by isntalling all the plugins needed. For this you execute in the root of the proyect the command

```sh
composer install
```
After its installed all the plugins you can proceed to configure the consumer and producer

## Rabbitmq server configuration

Go to the *src/Config/local.php* and set the url, port, username and password of the RabbitMQ server.

```php
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
        'mailchimp' => [
            'api_key' => '123456789'
        ]
    ]
];
```
## Mail server configuration
If you are using the php_mail example, its important to set the configuration of smtp server on the php.ini.
If you are using the mailchimp api plugin for sending the email, you need only add de api_key in *src/Config/local.php* 

```php
    ...
    'mailchimp' => [
        'api_key' => 'XXXXXXXXXXX'
    ]
```

### Note: Before you start the consumer and the producer, you need to start your RabbitMQ server first

## How to run the Producer

In the root directory of the project, then execute the command
```sh
php initProducer.php
```


## How to run the Consumer with the example

In the root directory of the project. If you wanna test the PHP Mail Example, execute
```sh
php initConsumerMailPhp.php
```

If you wanna test the Mailchimp Example, execute
```sh
php initConsumerMailchimp.php
```