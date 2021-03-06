<?php

// include('php/recaptcha.php');
require('php/vendor/autoload.php');

$sendToEmail = "contact@venteausangcher.com";

// Check for header injections
function has_header_injection($str) {
    return preg_match("/[\r\n]/", $str);
}

if (isset($_POST["submit"])) {
    if (isset($_POST['first-name'])) { $firstName = trim(filter_var($_POST['first-name'], FILTER_SANITIZE_STRING)); }
    if (isset($_POST['last-name'])) { $lastName = trim(filter_var($_POST['last-name'], FILTER_SANITIZE_STRING)); }
    if (isset($_POST['email'])) { $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)); }
    if (isset($_POST['message'])) { $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING); }
    if (isset($_POST['human'])) { $human = trim(filter_var($_POST['human'], FILTER_SANITIZE_NUMBER_INT)); }
    // if (isset($_POST['g-recaptcha-response'])) {
    //     $recaptcha = new \ReCaptcha\ReCaptcha($secret);
    //     $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
    // }

    // Check to see if name or eail have header injections
    if (has_header_injection($firstName) || has_header_injection($lastName) || has_header_injection($email) || has_header_injection($human)) {
        die(); //kills script if true
    }

    // Checks to make sure none of the fields are empty
    if ($firstName === "") {
        $errFirstName = '<p class="text-danger">Tous les champs doivent être complétés</p>';
    }
    if ($lastName === "") {
        $errLastName = '<p class="text-danger">Tous les champs doivent être complétés</p>';
    }
    if ($email === "") {
        $errEmail = '<p class="text-danger">Tous les champs doivent être complétés</p>';
    }
    if ($message === "") {
        $errMessage = '<p class="text-danger">Tous les champs doivent être complétés</p>';
    }
    if ($human === "") {
        $errHuman = '<p class="text-danger">Tous les champs doivent être complétés</p>';
    }

    // Validates the first and last name fields
    if (!(preg_match('/^[a-zA-Z ]*$/', $firstName))) {
        $errFirstName = '<p class="text-danger">Lettres et espaces seulement</p>';
    }
    if (!(preg_match('/^[a-zA-Z ]*$/', $lastName))) {
        $errLastName = '<p class="text-danger">Lettres et espaces seulement</p>';
    }

    // Validates the email field
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errEmail = '<p class="text-danger">Format d\'adresse invalide</p>';
    }

    // Changes $human from a string to a number, then checks if the answer is correct
    $human = intval($human);
    if ($human !== 5) {
        $errHuman = '<p class="text-danger">Votre anti-spam est incorrect</p>';
    }

    // If no errors, send the email!
    if (!$errFirstName && !$errLastName && !$errEmail && !$errMessage && !$errHuman) {
        $to =       $sendToEmail;
        $subject =  "Message from $firstName $lastName";
        $body =     "From: $firstName $lastName\r\n" .
                    "Email: $email\r\n" .
                    "Message:\r\n$message";
        $replyto =  "From: $email\r\n" .
                    "Reply-To: $to";

        if (mail ($to, $subject, $body, $replyto)) {
            // sends success message to HTML
            $msg = "Merci!";
            // sets class of success message for bootstrap
            $alert = "alert-success";
        } else {
            //sends error message to HTML
            $msg = "Une erreur est survenue dans l'envoi du message";
            // sets class of danger message for bootstrap
            $alert = "alert-danger";
        }
    }
}
?>
