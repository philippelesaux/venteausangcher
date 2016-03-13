<?php
    // Check for header injections
    function has_header_injection($str) {
        return preg_match("/[\r\n]/", $str);
    }

    if (isset($_POST["submit"])) {
        $firstName = trim($_POST['first-name']);
        $lastName = trim($_POST['last-name']);
        $email = trim($_POST['email']);
        $message = $_POST['message'];
        $human = intval(trim($_POST['human']));

        // Check to see if name or eail have header injections
        if (has_header_injection($firstName) || has_header_injection($lastName) || has_header_injection($email) || has_header_injection($human)) {
            die(); //kills script if true
        }

        $from = 'Contact Form';
        $to = 'lesauxp@gmail.com';
        $subject = 'Message from Contact Form';
        $body = "From: $name\n Email: $email\n Message:\n $message";

        if (!$_POST['first-name']) {
            $errFirstName = 'Please enter your first name';
        }
        if (!$_POST['last-name']) {
            $errLastName = 'Please enter your last name';
        }
        if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errEmail = 'Please enter a valid email address';
        }
        if (!$_POST['message']) {
            $errMessage = 'Please enter your message';
        }
        if ($human !== 5) {
            $errHuman = 'Your anti-spam is incorrect';
        }

        if (!$errFirstName && !$errLastName && !$errEmail && !$errMessage && !$errHuman) {
            mail ($to, $subject, $body, $from);
        }
    }
?>
