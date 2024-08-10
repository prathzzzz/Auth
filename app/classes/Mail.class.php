<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once('./app/init.php');

class Mail {
    public static function getMailer(): PHPMailer {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        //
        $mail->Host = AppConfig::getInstance()->MAIL_HOST;

        $mail->Port = AppConfig::getInstance()->MAIL_PORT;
        $mail->Username = AppConfig::getInstance()->MAIL_USERNAME;
        $mail->Password = AppConfig::getInstance()->MAIL_PASSWORD;

        $mail->setFrom( AppConfig::getInstance()->MAIL_FROM_ADDRESS,AppConfig::getInstance()->APP_NAME);
        $mail->isHTML(true);

        return $mail;
    }
}