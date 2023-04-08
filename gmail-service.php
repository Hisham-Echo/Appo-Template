<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

  // Validate form data
  if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    die('Please fill in all required fields.');
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die('Please provide a valid email address.');
  }

  // Generate CSRF token
  session_start();
  $token = bin2hex(random_bytes(32));
  $_SESSION['csrf_token'] = $token;

  // Send email
  $mail = new PHPMailer(true);
  try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server hostname
    $mail->SMTPAuth = true;
    $mail->Username = 'h.echo2004@gmail.com'; // Replace with your SMTP username
    $mail->Password = '00747@Google.Tony'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls'; // Replace with your encryption method (tls or ssl)
    $mail->Port = 587; // Replace with your SMTP port number

    // Email delivery and security best practices
    $mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion()); // Add X-Mailer header

    //Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress('h.echo2004@gmail.com'); // Replace with your email address or recipient's email address

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'New form submission from your educational website';
    $mail->Body    = "Name: $name<br>Email: $email<br>Message: $message";


    $mail->send();
    echo 'Thank you for your submission.';
  } catch (Exception $e) {
    echo 'There was a problem sending your message: ' . $mail->ErrorInfo;
  }
}
?>