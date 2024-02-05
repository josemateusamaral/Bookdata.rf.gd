<?php
// Inclua o arquivo do PHPMailer
require 'vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->Debugoutput = 'html';
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; //Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; //Set the encryption system to use - ssl (deprecated) or tls
	$mail->Username = "josemateusamaral@gmail.com";
	$mail->Password = "xuzrjrkophewlezp";
	$mail->setFrom('from@example.com', 'First Last');
	$mail->addAddress('jose.amaral@sottama.com', 'John Doe');
	$mail->Subject = "PHPMailer GMail SMTP test";
	$mail->msgHTML("for test, please ignore.");
	$mail->IsHTML(true);
	$mail->SMTPOptions = array(
		'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
		)
	);
			
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message has been sent";
	}
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
}

?>