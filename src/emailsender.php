<?php
namespace floraqueen_rabbitmq;
use MailchimpTransactional\ApiClient;

require 'vendor/autoload.php';

class EmailSender {

    public $mailchimp_key = '6Lr6qIveBJFK8s7RahEGvA';
    public $from_email = 'franciscoTrompiz@amaris.com';

    public function __construct()
    {

    }

    public function sendEmailViaPHP($data_to_send)
    {
        $to      = $data_to_send['email'];
        $subject = $data_to_send['subject'];
        $message = $data_to_send['message'];
        $headers = 'From: ' . $this->from_email . "\r\n" .
            'Reply-To: ' . $this->from_email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    public function sendEmailViaMailChimp($data_to_send)
    {
        try
        {
            $mailchimp = new ApiClient();
            $mailchimp->setApiKey($this->mailchimp_key);

            $response = $mailchimp->messages->send([
                "message" => [
                    "from_email" => $this->from_email,
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
