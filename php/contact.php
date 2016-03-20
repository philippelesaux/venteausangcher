<?php

include('php/recaptcha.php');
require('php/vendor/autoload.php');

$sendToEmail = "lesauxp@gmail.com";

// Check for header injections
function has_header_injection($str) {
    return preg_match("/[\r\n]/", $str);
}

if (isset($_POST["submit"])) {
    if (isset($_POST['first-name'])) { $firstName = trim($_POST['first-name']); }
    if (isset($_POST['last-name'])) { $lastName = trim($_POST['last-name']); }
    if (isset($_POST['email'])) { $email = trim($_POST['email']); }
    if (isset($_POST['message'])) { $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING); }
    if (isset($_POST['human'])) { $human = trim($_POST['human']); }
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
    }

    // Check to see if name or eail have header injections
    if (has_header_injection($firstName) || has_header_injection($lastName) || has_header_injection($email) || has_header_injection($human)) {
        die(); //kills script if true
    }

    // Checks to make sure none of the fields are empty
    if ($firstName === "") {
        $errFirstName = '<p class="text-danger">Sorry, all fields are required.</p>';
    }
    if ($lastName === "") {
        $errLastName = '<p class="text-danger">Sorry, all fields are required.</p>';
    }
    if ($email === "") {
        $errEmail = '<p class="text-danger">Sorry, all fields are required.</p>';
    }
    if ($message === "") {
        $errMessage = '<p class="text-danger">Sorry, all fields are required.</p>';
    }
    if ($human === "") {
        $errHuman = '<p class="text-danger">Sorry, all fields are required.</p>';
    }

    // Validates the first and last name fields
    if (!(preg_match('/^[a-zA-Z ]*$/', $firstName))) {
        $errFirstName = '<p class="text-danger">Sorry, names must be letters and spaces only.</p>';
    }
    if (!(preg_match('/^[a-zA-Z ]*$/', $lastName))) {
        $errLastName = '<p class="text-danger">Sorry, names must be letters and spaces only.</p>';
    }

    // Validates the email field
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errEmail = '<p class="text-danger">Invalid email format.</p>';
    }

    // Changes $human from a string to a number, then checks if the answer is correct
    $human = intval($human);
    if ($human !== 5) {
        $errHuman = '<p class="text-danger">Your anti-spam is incorrect</p>';
    }

    // If no errors, send the email!
    if (!$errFirstName && !$errLastName && !$errEmail && !$errMessage && !$errHuman && $resp->isSuccess()) {
        $to =       $sendToEmail;
        $subject =  "Message from $firstName $lastName";
        $body =     "From: $firstName $lastName\r\n" .
                    "Email: $email\r\n" .
                    "Message:\r\n$message";
        $replyto =  "From: $email\r\n" .
                    "Reply-To: $to";

        if (mail ($to, $subject, $body, $replyto)) {
            $msg = "Merci!";
            $alert = "alert-success";
        } else {
            $msg = "There was a problem sending the message.";
            $alert = "alert-danger";
        }
    }
}
?>
