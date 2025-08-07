<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // autoload လုပ်တာအရေးကြီးတယ်

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mail
{

    public function verifyMail($recipient_mail, $recipient_name, $token)
    {
        // Load Composer's autoloader
        require '../vendor/autoload.php';

        try {

            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = false;// Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host = 'smtp.gmail.com';// Set the SMTP server to send through
            $mail->SMTPAuth = true;// Enable SMTP authentication
            $mail->Username = 'aeindiasoemyint@gmail.com';// SMTP username
            $mail->Password = 'luaj xcdj nyvh usxg';// SMTP password
            $mail->SMTPSecure = 'tls';// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = 587;// TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('aeindiasoemyint@gmail.com', 'Central Cinema');
            $mail->addAddress($recipient_mail, $recipient_name);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Verify Mail';
            $mail->Body = "<b> <a href='$token' target='_blank'> Click here </a></b> to verify your registration.";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $success = $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
    private function sendMail($recipient_mail, $subject, $body)
    {
        require '../vendor/autoload.php';

        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = false;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'aeindiasoemyint@gmail.com';
            $mail->Password = 'luaj xcdj nyvh usxg'; // ❗ App password only, don't expose this in production
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('aeindiasoemyint@gmail.com', 'Central Cinema');
            $mail->addAddress($recipient_mail);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            return $mail->send(); // returns true or false
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo); // for debugging
            return false;
        }
    }

    public function sendOtpMail($email, $otp)
    {
        $subject = "Your OTP Code";
        $body = "Your OTP code is: <strong>$otp</strong>. It will expire in 60 seconds.";

        // Send email
        return $this->sendMail($email, $subject, $body);
    }

}





?>