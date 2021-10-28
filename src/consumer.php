<?php
namespace floraqueen_rabbitmq;
require('vendor/autoload.php');
require('emailsender.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use floraqueen_rabbitmq\EmailSender;

class Consumer {

    /**
     * @var AMQPStreamConnection $connection
     */
    public $connection;
    /**
     * @var AMQPChannel $channel
     */
    public $channel;

    /**
     * @var EmailSender $email_sender
     */
    public $email_sender;

    public $queue_name = 'jobs';

    public $url = 'localhost';
    public $port = '5672';

    public $user = 'guest';
    public $password = 'guest';


    public function __construct()
    {
        $this->email_sender = new EmailSender();
    }

    public function connectToRabbitMQ()
    {
        $this->connection = new AMQPStreamConnection($this->url, 5672, $this->user, $this->password);
        $this->channel = $this->connection->channel();
    }

    public function initializeWaiting()
    {
        $callback = function ($msg) {
            echo ' [x] Received ', "\n";
            print_r($msg->body);
            echo "\n";

            $msg_encoded = json_decode($msg->body,true);

            //$this->email_sender->sendEmailViaPHP($msg_encoded);
            $this->email_sender->sendEmailViaMailChimp($msg_encoded);
        };

        $this->channel->basic_consume($this->queue_name, '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            echo ' [x] Waiting for messages ', "\n";
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


