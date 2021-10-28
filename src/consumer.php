<?php
require('vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use EmailSender;

class Consumer {

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

    }

    public function initializeWaiting()
    {
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $this->channel->basic_consume('test_queue', '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
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
