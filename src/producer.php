<?php
namespace floraqueen_rabbitmq;
require('vendor/autoload.php');
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

    public $queue_name = 'jobs';

    public $url = 'localhost';
    public $port = '5672';

    public $user = 'guest';
    public $password = 'guest';

    public function __construct()
    {
    }

    public function connectToRabbitMQ()
    {
        $this->connection = new AMQPStreamConnection($this->url, 5672, $this->user, $this->password);
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($this->queue_name, false, true, false, false);
    }

    public function sendMessage($msg_json)
    {
        $msg_to_send = new AMQPMessage(json_encode($msg_json));
        $this->channel->basic_publish($msg_to_send, '', $this->queue_name);

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


