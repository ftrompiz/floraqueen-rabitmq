<?php
namespace Trobe\FloraqueenRabitmq\Classes;
use MailchimpTransactional\ApiClient;
require_once __DIR__.'/../../vendor/autoload.php';

class EmailSender {

    const SENDER_TYPE_MAIL_PHP = 'MAIL_PHP';
    const SENDER_TYPE_MAILCHIMP = 'MAILCHIMP';

    /**
     * @var string $sender_type
     */
    public $sender_type;

    /**
     * @var array $config
     */
    public $config;

    public function __construct($sender_type)
    {
        $this->config = include(__DIR__.'/../config/local.php');
        $this->sender_type = $sender_type;
    }
    public function sendEmail($data_to_send){
        switch ($this->sender_type) {
            case self::SENDER_TYPE_MAIL_PHP:
                $this->sendEmailViaPHP($data_to_send);
                break;
            case self::SENDER_TYPE_MAILCHIMP:
                $this->sendEmailViaMailChimp($data_to_send);
                break;
        }
    }

    public function sendEmailViaPHP($data_to_send)
    {
        $to      = $data_to_send['email'];
        $subject = $data_to_send['subject'];
        $message = $data_to_send['message'];
        $headers = 'From: ' . $this->config['mailer']['from_email'] . "\r\n" .
            'Reply-To: ' . $this->config['mailer']['from_email'] . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        echo ' [x] Sending email via mail ', "\n";
        mail($to, $subject, $message, $headers);
    }

    public function sendEmailViaMailChimp($data_to_send)
    {
        try
        {
            $mailchimp = new ApiClient();
            $mailchimp->setApiKey($this->config['mailer']['mailchimp']['api_key']);

            echo ' [x] Sending email via MailChimp ', "\n";
            $response = $mailchimp->messages->send([
                "message" => [
                    "from_email" => $this->config['mailer']['from_email'],
                    "subject" => $data_to_send['subject'],
                    "text" => $data_to_send['message'],
                    "to" => [
                        [
                            "email" => $data_to_send['email'],
                            "type" => "to"
                        ]
                    ]
                ]
            ]);

            print_r($response);
        }
        catch (\Exception $e)
        {
            echo 'Error: ', $e->getMessage(), "\n";
        }
    }
}