<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function sendToFollowUpBoss($postData)
{
    $apiUrl = 'https://api.followupboss.com/v1/events';
    $authToken = 'ZmthXzAwTlVCbDF2bGZzRXhyZlZXMmNCYVlqMXJXZzJ6NUNoN2c6';

    // Split name into first and last name
    $nameParts = explode(' ', $postData['name']);
    $firstName = $nameParts[0];
    $lastName = implode(' ', array_slice($nameParts, 1));

    // Prepare Follow Up Boss payload
    $payload = array(
        'person' => array(
            'contacted' => false,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'emails' => array(
                array('value' => $postData['email'])
            ),
            'phones' => array(
                array('value' => $postData['phone'])
            ),
            'tags' => array('Scarborough', 'Thompson Towns')
        ),
        'source' => 'thompsontowns.ca',
        'system' => 'Custom Website',
        'type' => 'Inquiry',
        'message' => $postData['message']
    );

    // Setup cURL request
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Basic ' . $authToken
    ));

    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode >= 200 && $httpCode < 300;
}


$mail = new PHPMailer;

$mail->isSMTP(); // Set mailer to use SMTP
$mail->Host = 'mail.thompsontowns.ca'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'info@thompsontowns.ca'; // SMTP username
$mail->Password = 'mail@thompsontowns'; // SMTP password
$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587; // TCP port to connect to

$mail->setFrom('info@thompsontowns.ca', $_POST['name']);
$mail->addAddress('contact@homebaba.ca');




$mail->addReplyTo($_POST['email']);
$mail->isHTML(true);

$mail->Subject = "Thompson Towns Scarborough - Landing Page Inquiry";
$message = '

';
$message .= '';
$message .= "
            Name:
            " . strip_tags($_POST['name']) . "
        <br/>";
$message .= "
            Phone:
            " . strip_tags($_POST['phone']) . "
       <br/> ";
$message .= "
            Email:
            " . strip_tags($_POST['email']) . "
       <br/> ";
$message .= "
        Realtor or working with one?:
        " . strip_tags($_POST['realtor']) . "
    <br/>";
$message .= "
            Message : 
            " . strip_tags($_POST['message']) . "
       <br/> ";
$message .= "
            Source : 
            thompsontowns.ca
        ";
$message .= "";
$message .= "

";

$mail->Body = $message;
$mail->AltBody = $_POST['message'] . $_POST['email'] . $_POST['name'] . $_POST['phone'];

try {
    $emailSent = $mail->send();
    $fubSent = sendToFollowUpBoss($_POST);

    if ($emailSent && $fubSent) {
        $_SESSION["success"] = "Application submitted.";
        header("Location: ./thankyou/");
        exit();
    } else {
        throw new Exception("Failed to send email or Follow Up Boss notification");
    }
} catch (Exception $e) {
    $_SESSION["error"] = "Application not submitted!";
    error_log("Form submission error: " . $e->getMessage());
    header("Location: index.php");
    exit();
}

?>