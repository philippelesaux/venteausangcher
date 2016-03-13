<?php
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
    if (!$errFirstName && !$errLastName && !$errEmail && !$errMessage && !$errHuman) {
        $to =       "contact@venteausangcher.com";
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

    // // Check for header injections
    // function has_header_injection($str) {
    //     return preg_match("/[\r\n]/", $str);
    // }
    //
    // if (isset($_POST["submit"])) {
    //     $firstName = trim($_POST['first-name']);
    //     $lastName = trim($_POST['last-name']);
    //     $email = trim($_POST['email']);
    //     $message = $_POST['message'];
    //     $human = intval(trim($_POST['human']));
    //
    //     // Check to see if name or eail have header injections
    //     if (has_header_injection($firstName) || has_header_injection($lastName) || has_header_injection($email) || has_header_injection($human)) {
    //         die(); //kills script if true
    //     }
    //
    //     $from = 'Contact Form';
    //     $to = 'lesauxp@gmail.com';
    //     $subject = 'Message from Contact Form';
    //     $body = "From: $name\n Email: $email\n Message:\n $message";
    //
    //     if (!$_POST['first-name']) {
    //         $errFirstName = 'Please enter your first name';
    //     }
    //     if (!$_POST['last-name']) {
    //         $errLastName = 'Please enter your last name';
    //     }
    //     if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    //         $errEmail = 'Please enter a valid email address';
    //     }
    //     if (!$_POST['message']) {
    //         $errMessage = 'Please enter your message';
    //     }
    //     if ($human !== 5) {
    //         $errHuman = 'Your anti-spam is incorrect';
    //     }
    //
    //     if (!$errFirstName && !$errLastName && !$errEmail && !$errMessage && !$errHuman) {
    //         mail ($to, $subject, $body, $from);
    //     }
    // }
?>
