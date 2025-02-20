<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
	protected $mailer;

	public function __construct(PHPMailer $mailer)
	{
		$this->mailer = $mailer;
		$mailer->isSMTP();                                            //Send using SMTP
		$mailer->Host       = $_ENV['MAIL_HOST'];                     //Set the SMTP server to send through
		$mailer->Username   = $_ENV['MAIL_USERNAME'];                 //SMTP username
		$mailer->Password   = $_ENV['MAIL_PASSWORD'];                 //SMTP password
		$mailer->Port       = $_ENV['MAIL_PORT'];                   	//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		$mailer->SMTPAuth   = $_ENV['MAIL_SMTP_AUTH'];                //Enable SMTP authentication
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   
	}

	public function sendMail($to, $subject, $body)
	{
		$this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
		$this->mailer->addAddress($to);
		$this->mailer->Subject = $subject;
		$this->mailer->Body = $body;

		$this->mailer->send();
	}

	public function sendPasswordResetEmail($email, $token)
	{
		$subject = 'Password Reset';
		$body = 'Click the following link to reset your password: ' . $_ENV['SITE_URL'] . '/admin/reset-password?token=' . $token;
		$this->sendMail($email, $subject, $body);
	}
}
