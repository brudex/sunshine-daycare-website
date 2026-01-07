<?php
/**
 * Contact Form Handler
 * Sends general contact, tour and other enquiries to the main info email address
 */

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact-us.html');
    exit;
}

// Email configuration - all contact, tour and general enquiries go to the main info inbox
$to_emails = [
    'info@sunshinechildcareservices.co.uk',
];

// Sanitize and validate input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Get and sanitize form data
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

// Prepare email content
$email_subject = !empty($subject) ? $subject : 'New Contact Form Submission - Sunshine Child-Care Nursery';

$email_body = "New Contact Form Submission\n\n";
$email_body .= "================================\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Phone: " . ($phone ? $phone : 'Not provided') . "\n";
$email_body .= "Subject: " . $subject . "\n\n";
$email_body .= "Message:\n";
$email_body .= "--------------------------------\n";
$email_body .= $comments . "\n\n";
$email_body .= "================================\n";
$email_body .= "Submitted: " . date('Y-m-d H:i:s') . "\n";
$email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";

// Email headers
$headers = [];
$headers[] = 'From: Sunshine Child-Care Nursery <noreply@sunshinechildcareservices.co.uk>';
$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$headers_string = implode("\r\n", $headers);

// Send email to all recipients
$mail_sent = true;
$failed_recipients = [];

foreach ($to_emails as $to_email) {
    if (!mail($to_email, $email_subject, $email_body, $headers_string)) {
        $mail_sent = false;
        $failed_recipients[] = $to_email;
    }
}

// Return JSON response
header('Content-Type: application/json');

if ($mail_sent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you for contacting us! We will get back to you soon.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sorry, there was an error sending your message. Please try again later or contact us directly.'
    ]);
    
    // Log error (optional - for debugging)
    error_log('Contact form email failed for: ' . implode(', ', $failed_recipients));
}
?>

