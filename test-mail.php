<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'pallavijadar2000@gmail.com';  // Replace with your Gmail address
$mail->Password = 'Pj@123456';  // Use app password if 2FA is enabled
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
// Alternatively, use SSL on port 465 (older method)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;

$mail->setFrom('your-email@gmail.com', 'Your Name');
$mail->addAddress('recipient-email@example.com');  // Replace with the recipient's email address
$mail->isHTML(true);
$mail->Subject = 'Test Email';
$mail->Body    = 'This is a test email to check PHPMailer functionality.';

if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
?>
