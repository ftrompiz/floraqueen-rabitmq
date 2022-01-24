<?php
namespace Trobe\FloraqueenRabitmq\Classes;
require_once __DIR__.'/../../vendor/autoload.php';
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
    public $port = 5672;

    public $user = 'guest';
    public $password = 'guest';


    public function __construct()
    {
        // inicializamos la clase email sender para ser utilizada luego cuando se tenga que enviar algo
        $this->email_sender = new EmailSender();
    }

    public function connectToRabbitMQ()
    {
        //inicar la conection al servidor de rabbitmq
        $this->connection = new AMQPStreamConnection($this->url, $this->port, $this->user, $this->password);
        // nos conectamos al canal donde se enviara los mensajes
        $this->channel = $this->connection->channel();
    }

    public function initializeWaiting()
    {
        $this->channel->basic_consume($this->queue_name, '', false, true, false, false, function ($msg) {
            // cuando llegue el mensaje pintamos el resultado
            echo ' [x] Received ', "\n";
            print_r($msg->body);
            echo "\n";

            //hacemos un json decoe para que podamos acceder a la informacion mas facilmente
            $msg_encoded = json_decode($msg->body,true);

            // Envio de mensaje via PHP
            $this->email_sender->sendEmailViaPHP($msg_encoded);
            // Envio de mensaje via MailChimp
            //$this->email_sender->sendEmailViaMailChimp($msg_encoded);
        });

        // iniciar loop infinito donde el consumer esperará los mensajes que este en la conal
        while ($this->channel->is_consuming()) {
            // se imprimen los mensajes que  llegan de la cola
            echo ' [x] Waiting for messages ', "\n";
            $this->channel->wait();
        }
    }

    /**
     * @throws Exception
     */
    public function closeConnection()
    {
        // cerramos el canal
        $this->channel->close();
        // cerramos la conneccion
        $this->connection->close();
    }
}