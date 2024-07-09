<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require_once $_SERVER['DOCUMENT_ROOT'].'/phpmailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/phpmailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/phpmailer/src/SMTP.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

// Create a new PHPMailer instance
function sendEmail($recipient = null, $recipient_name = null, $subject = null, $message = null) {
    $mail = new PHPMailer(true);

    try {
        // Set mailer to use SMTP
        $mail->isSMTP();

        // Enable SMTP debugging (for testing)
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;

        // Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';

        // Enable TLS encryption
        $mail->SMTPSecure = 'tls';

        // Set the SMTP port (465 for SSL, 587 for TLS)
        $mail->Port = 587;

        // Set Gmail credentials
        $mail->SMTPAuth = true;
        global $_EMAIL_ADDRESS, $_EMAIL_PASSWORD;
        $mail->Username = $_EMAIL_ADDRESS;
        $mail->Password = $_EMAIL_PASSWORD;
        // Set the 'from' address and recipient
        $mail->setFrom('kgvh@gmail.com', 'notification-system');
        $mail->addAddress($recipient, $recipient_name);

        // Set email subject and body
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(true);
        // Send the email
        $mail->send();

    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

?>