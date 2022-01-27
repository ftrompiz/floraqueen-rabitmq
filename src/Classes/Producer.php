<?php
namespace Trobe\FloraqueenRabitmq\Classes;
require_once __DIR__.'/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    /**
     * @var AMQPStreamConnection $connection
     */
    public $connection;
    /**
     * @var AMQPChannel $channel
     */
    public $channel;

    public $config;


    public function __construct()
    {
        $this->config = include(__DIR__.'/../config/local.php');
    }

    public function connectToRabbitMQ()
    {
        // inicializamos la conneccion al servidor de rabitmq
        $this->connection = new AMQPStreamConnection(
            $this->config['rabbitmq']['url'],
            $this->config['rabbitmq']['port'],
            $this->config['rabbitmq']['username'],
            $this->config['rabbitmq']['password']);
        // nos conectamos al canal
        $this->channel = $this->connection->channel();

        // creamos una cola, en caso que exista en el servidor de rabbitmq, esta no se vuelve a crear
        $this->channel->queue_declare($this->config['rabbitmq']['queue_name'], false, true, false, false);
    }

    public function sendMessage($msg_array)
    {
        // creamos el mensaje que sera enviado a la cola
        $msg_to_send = new AMQPMessage(json_encode($msg_array));
        // se envia a la cola el mensaje creado
        $this->channel->basic_publish($msg_to_send, '', $this->config['rabbitmq']['queue_name']);

        // imprimimos el mensaje enviado en el terminal
        echo " [x] Sent message \n";
        print_r($msg_to_send->body);
        echo "\n";
    }

    public function createMessage($email, $subject, $message)
    {
        return [
            "email" => $email,
            "subject" => $subject,
            "message" => $message
        ];
    }

    /**
     * @throws \Exception
     */
    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}