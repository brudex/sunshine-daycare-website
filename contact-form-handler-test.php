<?php
/**
 * Contact Form Handler - TEST VERSION
 * This version always returns success for local testing without email configuration
 * 
 * TO USE: Rename this file to contact-form-handler.php temporarily for testing
 * OR: Update the form action in contact-us.html to point to this file
 */

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get and sanitize form data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : 'Contact Form Submission';
$comments = isset($_POST['comments']) ? sanitize_input($_POST['comments']) : '';

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!validate_email($email)) {
    $errors[] = 'Invalid email format';
}

if (empty($comments)) {
    $errors[] = 'Message is required';
}

// If there are errors, return JSON response
if (!empty($errors)) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => implode(', ', $errors)
    ]);
    exit;
}

// Log the form submission (for testing purposes)
$log_entry = date('Y-m-d H:i:s') . " - Contact Form Submission\n";
$log_entry .= "Name: $name\n";
$log_entry .= "Email: $email\n";
$log_entry .= "Phone: $phone\n";
$log_entry .= "Subject: $subject\n";
$log_entry .= "Message: $comments\n";
$log_entry .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$log_entry .= "----------------------------------------\n\n";

// Write to log file (optional - for debugging)
$log_file = __DIR__ . '/contact-form-test.log';
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Return success response (for testing - no actual email sent)
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Thank you for contacting us! We will get back to you soon. (TEST MODE - Email not sent)'
]);
?>

