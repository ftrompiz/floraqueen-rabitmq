<?php
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

    public function sendMessage()
    {
        $msg_test = [
            "number" => 9
        ];
        $msg = new AMQPMessage(json_encode($msg_test));
        $this->channel->basic_publish($msg, '', $this->queue_name);

        echo " [x] Sent 'Hello World!'\n";
    }

    /**
     * @throws Exception
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
    $prod->sendMessage();
    $prod->closeConnection();
}
catch (Exception $exception)
{
    echo "Error en la connection".PHP_EOL;
    print_r($exception);
}


