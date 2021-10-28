<?php
require 'vendor/autoload.php';

class EmailSender {


    public function __construct()
    {

    }

    public function sendEmailViaPHP($data_to_send)
    {
        $to      = 'nobody@example.com';
        $subject = 'the subject';
        $message = 'hello';
        $headers = 'From: webmaster@example.com'       . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
}