<?php
require_once __DIR__.'/vendor/autoload.php';
use Trobe\FloraqueenRabitmq\Classes\Consumer;

$cons = new Consumer();
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