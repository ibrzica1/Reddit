<?php

namespace Reddit\services;

require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

class MailService
{
  protected $mailer;

  public function __construct()
  {
    $dotenv = Dotenv::createImmutable(__DIR__. '/../../');
    $dotenv->load();

    $this->mailer = new PHPMailer();

    $this->mailer->isSMTP();
    $this->mailer->Host = $_ENV['MAILTRAP_HOST'];
    $this->mailer->SMTPAuth = true;
    $this->mailer->Username = $_ENV['MAILTRAP_USERNAME'];
    $this->mailer->Password = $_ENV['MAILTRAP_PASSWORD'];
    $this->mailer->Port = $_ENV['MAILTRAP_PORT'];
  }

  public function sendMail($address,$title,$body)
  {
    $this->mailer->ClearAllRecipients();
    $this->mailer->setFrom('igorbrzica1@gmail.com','Igor');
    $this->mailer->addAddress($address);
    $this->mailer->Subject = $title;
    $this->mailer->Body = $body;
    $this->mailer->IsHTML(false);
    $this->mailer->send();
  }

  public function welcomeMail($address,$name)
  {
    $title = "Welcome";
    $body = "Welcome {$name} on our Reddit site!";
    $this->mailer->ClearAllRecipients();
    $this->mailer->setFrom('igorbrzica1@gmail.com','Igor');
    $this->mailer->addAddress($address);
    $this->mailer->Subject = $title;
    $this->mailer->Body = $body;
    $this->mailer->IsHTML(false);
    $this->mailer->send();
  }
}