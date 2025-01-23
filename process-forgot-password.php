<?php
session_start();
include 'db.php';  // Include your database connection file

// Load PHPMailer
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email from the form
    $email = $_POST['email'];

    // Query the database to check if the email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Generate a unique reset token
        $reset_token = bin2hex(random_bytes(16));  // Unique token

        // Store the reset token and timestamp in the database for this user
        $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));  // Token expires in 1 hour
        $update_query = "UPDATE users SET reset_token = '$reset_token', reset_token_expiry = '$expiry_time' WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // Create the reset link
        $reset_link = "http://localhost/placement-portal-main/reset-password.php?token=$reset_token";

        // Send the password reset email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();                                          // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                            // Set the SMTP server to Gmail
            $mail->SMTPAuth = true;                                    // Enable SMTP authentication
            $mail->Username = 'pallavijadar2000@gmail.com';            // SMTP username (your Gmail address)
            $mail->Password = 'Pj@123456';                             // SMTP password (use App password if 2FA is enabled)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption
            $mail->Port = 587;                                         // TCP port to connect to (587 for TLS)

            // Recipients
            $mail->setFrom('no-reply@placementportal.com', 'Placement Portal');  // From email address
            $mail->addAddress($email);                                 // Add recipient email (user's email)

            // Content
            $mail->isHTML(true);                                      // Set email format to HTML
            $mail->Subject = 'Password Reset Request';                 // Subject of the email
            $mail->Body    = "Click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";  // Password reset link in the email body

            // Enable debugging to check what happens during the SMTP process
            $mail->SMTPDebug = 2;  // Enable debugging (1 = client, 2 = client and server)

            // Send the email
            $mail->send();
            $_SESSION['forgotPasswordSuccess'] = "Password reset link sent to your email!";
            header("Location: forgot-password.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['forgotPasswordError'] = "Failed to send email. Please try again later.";
            header("Location: forgot-password.php");
            exit();
        }
    } else {
        // User not found in the database
        $_SESSION['forgotPasswordError'] = "Email not found in our system!";
        header("Location: forgot-password.php");
        exit();
    }
}
?>
