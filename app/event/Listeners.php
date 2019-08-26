<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Listeners
{

    public $host;

    public function __construct()
    {

        $this->host = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}";

    }

    public function mailGonder($alici, $kbaslik, $govde)
    {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = false;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = SMTPHOST;
            $mail->Port = 465;
            $mail->isHTML(true);
            $mail->SetLanguage("tr", DIR . "vendor/phpmailer/phpmailer/language/");
            $mail->CharSet = "utf-8";
            $mail->Username = SMTPUSER;
            $mail->Password = SMTPPASS;
            $mail->SetFrom(FROM, SITEISMI);
            $mail->AddAddress($alici);
            $mail->Subject = $kbaslik;
            $mail->Body = $govde;

            $mail->send();

        } catch (Exception $e) {

            return "hata";

        }

        return "ok";

    }

}