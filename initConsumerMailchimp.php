<?php
require_once __DIR__.'/vendor/autoload.php';
use Trobe\FloraqueenRabitmq\Classes\Consumer;
use Trobe\FloraqueenRabitmq\Classes\EmailSender;

$cons = new Consumer(new EmailSender(EmailSender::SENDER_TYPE_MAILCHIMP));
try
{
    $cons->connectToRabbitMQ();
    $cons->initializeWaiting();
    $cons->closeConnection();
}
catch (Exception $exception)
{
    echo "An error has ocurred".PHP_EOL;
    print_r($exception);
}