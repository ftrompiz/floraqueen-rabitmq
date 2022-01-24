<?php
namespace Trobe\FloraqueenRabitmq\Classes;
require('vendor/autoload.php');
require('EmailSender.php');

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

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

            // Envio de mensaje via PHP
            $this->email_sender->sendEmailViaPHP($msg_encoded);
            // Envio de mensaje via MailChimp
            //$this->email_sender->sendEmailViaMailChimp($msg_encoded);
        };

        $this->channel->basic_consume($this->queue_name, '', false, true, false, false, $callback);

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