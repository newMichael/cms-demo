<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


function send_email()
{
	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = $_ENV['MAIL_HOST'];                     //Set the SMTP server to send through
		$mail->Username   = $_ENV['MAIL_USERNAME'];                 //SMTP username
		$mail->Password   = $_ENV['MAIL_PASSWORD'];                 //SMTP password
		$mail->Port       = $_ENV['MAIL_PORT'];                   	//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		$mail->SMTPAuth   = $_ENV['MAIL_SMTP_AUTH'];                //Enable SMTP authentication
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          //Enable implicit TLS encryption

		//Recipients
		$mail->setFrom('from@example.com', 'Mailer');
		$mail->addAddress('joe@example.net', 'Joe User');
		$mail->addReplyTo('info@example.com', 'Information');

		//Content
		$mail->isHTML(true);
		$mail->Subject = 'Here is the subject';
		$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		exit;
	}
}

function dd(...$data)
{
	echo '<pre>';
	foreach ($data as $d) {
		var_dump($d);
	}
	echo '</pre>';
	exit;
}
