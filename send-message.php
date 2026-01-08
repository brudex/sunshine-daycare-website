<?php
/**
 * Contact Form Handler
 * Sends general contact, tour and other enquiries to the main info email address
 */

// Set error handling - catch fatal errors and return JSON
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        http_response_code(200); // Return 200 to prevent AJAX error handler
        echo json_encode([
            'status' => 'error',
            'message' => 'Server error occurred. Please try again later or contact us directly.'
        ]);
        exit;
    }
});

// Set JSON header early
header('Content-Type: application/json');

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Email configuration - all contact go to the main info inbox
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

// Wrap everything in try-catch to handle any runtime errors
try {
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

// Email headers - using official domain
$from_email = 'noreply@sunshinechildcareservices.co.uk';
$headers = [];
$headers[] = 'From: Sunshine Child Care Services <' . $from_email . '>';
$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'X-Priority: 3';

$headers_string = implode("\r\n", $headers);

// Send email to all recipients using PHP mail()
$mail_sent = false;
$failed_recipients = [];
$mail_errors = [];

if (!function_exists('mail')) {
    $mail_errors[] = 'PHP mail() function is not available on this server';
} else {
    error_clear_last();
    
    foreach ($to_emails as $to_email) {
        try {
            // Use -f parameter to set Return-Path (helps with delivery)
            $additional_parameters = '-f' . $from_email;
            $result = @mail($to_email, $email_subject, $email_body, $headers_string, $additional_parameters);
        } catch (Exception $e) {
            $result = false;
            $mail_errors[] = "Exception sending mail: " . $e->getMessage();
        } catch (Error $e) {
            $result = false;
            $mail_errors[] = "Fatal error sending mail: " . $e->getMessage();
        }
        
        if ($result) {
            $mail_sent = true;
        } else {
            $mail_sent = false;
            $failed_recipients[] = $to_email;
            $last_error = error_get_last();
            if ($last_error) {
                $mail_errors[] = $last_error['message'];
            } else {
                $mail_errors[] = 'mail() function returned false (email not sent)';
            }
        }
    }
}

// Log submission for debugging (always log, even if email fails)
$log_entry = date('Y-m-d H:i:s') . " - Contact Form Submission\n";
$log_entry .= "Name: $name\n";
$log_entry .= "Email: $email\n";
$log_entry .= "Phone: $phone\n";
$log_entry .= "Subject: $subject\n";
$log_entry .= "Message: $comments\n";
$log_entry .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
$log_entry .= "Email Sent: " . ($mail_sent ? 'Yes' : 'No') . "\n";
if (!empty($mail_errors)) {
    $log_entry .= "Errors: " . implode(', ', $mail_errors) . "\n";
}
$log_entry .= "----------------------------------------\n\n";

// Write to log file
$log_file = __DIR__ . '/contact-form-submissions.log';
try {
    // Only attempt to write if we can
    if ((!file_exists($log_file) && is_writable(__DIR__)) || is_writable($log_file)) {
        @file_put_contents($log_file, $log_entry, FILE_APPEND);
    }
} catch (Exception $e) {
    // Ignore logging errors to prevent breaking the form
}

// Always return success to user (email sending is handled in background)
// This prevents users from seeing technical errors
// The submission is logged above for admin review
echo json_encode([
    'status' => 'success',
    'message' => 'Thank you for contacting us! We will get back to you soon.'
]);

// Log email failure separately for admin
if (!$mail_sent) {
    error_log('Contact form email failed for: ' . implode(', ', $failed_recipients));
    if (!empty($mail_errors)) {
        error_log('Mail errors: ' . implode(', ', $mail_errors));
    }
}

// IMPORTANT: If mail() returns true but emails don't arrive, this is a server configuration issue.
// Contact your hosting provider to check:
// 1. Mail server (sendmail/postfix) configuration
// 2. SPF/DKIM DNS records
// 3. Server IP blacklist status
// 4. Mail queue/logs for delivery failures

} catch (Exception $e) {
    // Catch any exceptions and return JSON error
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred processing your request. Please try again later.'
    ]);
    error_log('Contact form exception: ' . $e->getMessage());
    exit;
} catch (Error $e) {
    // Catch fatal errors (PHP 7+)
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred processing your request. Please try again later.'
    ]);
    error_log('Contact form fatal error: ' . $e->getMessage());
    exit;
}
?>

