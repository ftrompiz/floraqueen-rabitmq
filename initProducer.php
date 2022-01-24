<?php
require('vendor/autoload.php');
use Trobe\FloraqueenRabitmq\Classes\Producer;

$prod = new Producer();

try {
    $prod->connectToRabbitMQ();

    $msg = $prod->createMessage("trober131@gmail.com","prueba mq","Enviando mensaje prueba");

    $prod->sendMessage($msg);

    $prod->closeConnection();
}
catch (Exception $exception)
{
    echo "An error has ocurred".PHP_EOL;
    print_r($exception);
}