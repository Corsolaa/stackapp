<?php

declare(strict_types=1);

namespace StackSite\Core\Mailing;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailHandler
{
    private PHPMailer $mail;

    public function __construct(string $from, string $fromName)
    {
        $this->mail             = new PHPMailer(true);
        $this->mail->Host       = $_ENV['SMTP_HOST'] || "localhost";
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['SMTP_USER'] || "root";
        $this->mail->Password   = $_ENV['SMTP_PASS'] || "";
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = $_ENV['SMTP_PORT'] || 587;

        try {
            $this->mail->setFrom($from, $fromName);
        } catch (Exception) {
        }
    }

    public function setRecipient($email, $name = ''): bool
    {
        try {
            $this->mail->addAddress($email, $name);
            return true;
        } catch (Exception) {
            return false;
        }
    }

    function send(string $subject, string $body): bool
    {
        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;
        $this->mail->AltBody = strip_tags($body, ['<br>', '<b>']);

        try {
            $this->mail->send();
            return True;
        } catch (Exception) {
            return False;
        }
    }
}