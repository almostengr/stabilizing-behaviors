<?php

// Start the session to handle error messages and success messages
session_start();

// Function to sanitize input data
function sanitize($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Honeypot check: if 'website' field is filled, it's likely a bot
    if (!empty($_POST['website'])) {
        echo "Spam detected.";
        exit;
    }

    // Retrieve and sanitize form inputs
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $captcha = sanitize($_POST['captcha'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($message) || empty($captcha)) {
        echo "All fields are required.";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Validate captcha (static value: 3 + 4)
    if ($captcha != 7) {
        echo "Incorrect captcha answer.";
        exit;
    }

    // Prepare email
    $to = "your-email@example.com"; // Replace with your email address
    $subject = "New Contact Form Submission from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email\r\nReply-To: $email";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo "Your message has been sent successfully.";
    } else {
        echo "There was an error sending your message. Please try again later.";
    }
} else {
    // If not a POST request, redirect back to the form
    header("Location: index.html");
    exit;
}
