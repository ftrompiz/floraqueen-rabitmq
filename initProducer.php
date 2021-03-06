<?php
require_once __DIR__.'/vendor/autoload.php';
use Trobe\FloraqueenRabitmq\Classes\Producer;

$prod = new Producer();

try {
    $prod->connectToRabbitMQ();

    $msg = $prod->createMessage("trober131@gmail.com","using test mode","Enviando mensaje prueba");

    $prod->sendMessage($msg);

    $prod->closeConnection();
}
catch (Exception $exception)
{
    echo "An error has ocurred".PHP_EOL;
    print_r($exception);
}