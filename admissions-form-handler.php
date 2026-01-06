<?php
/**
 * Admissions Form Handler
 * Sends admissions form submissions to admissions and nursery manager emails
 */

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admissions-fees.html');
    exit;
}

// Email configuration
$to_emails = [
    'admissions@sunshinechildcareservices.co.uk',
    'sunshinenurserymanager@sunshinechildcareservices.co.uk'
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
// Parent/Guardian Information
$f_name = isset($_POST['f-name']) ? sanitize_input($_POST['f-name']) : '';
$l_name = isset($_POST['l-name']) ? sanitize_input($_POST['l-name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$address = isset($_POST['address']) ? sanitize_input($_POST['address']) : '';

// Child Information
$child_name = isset($_POST['child-name']) ? sanitize_input($_POST['child-name']) : '';
$child_age = isset($_POST['child-age']) ? sanitize_input($_POST['child-age']) : '';
$sessions = isset($_POST['session']) ? $_POST['session'] : [];

// Consent
$terms = isset($_POST['terms']) ? 'Yes' : 'No';
$privacy = isset($_POST['privacy']) ? 'Yes' : 'No';
$data_use = isset($_POST['data-use']) ? 'Yes' : 'No';

// Validation
$errors = [];

if (empty($f_name)) {
    $errors[] = 'First name is required';
}

if (empty($l_name)) {
    $errors[] = 'Last name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!validate_email($email)) {
    $errors[] = 'Invalid email format';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required';
}

if (empty($address)) {
    $errors[] = 'Street address is required';
}

if (empty($child_name)) {
    $errors[] = 'Child\'s name is required';
}

if (empty($child_age)) {
    $errors[] = 'Child\'s age is required';
}

if (empty($sessions)) {
    $errors[] = 'Please select at least one session preference';
}

if ($terms !== 'Yes') {
    $errors[] = 'You must agree to the Terms & Conditions';
}

if ($privacy !== 'Yes') {
    $errors[] = 'You must accept the Privacy Policy';
}

if ($data_use !== 'Yes') {
    $errors[] = 'You must consent to data use';
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

// Prepare session preferences text
$session_names = [
    'breakfast' => 'Breakfast Club (07:30–09:00)',
    'preschool' => 'Preschool (09:00–15:00)',
    'after-school' => 'After School Club (15:00–18:00)'
];

$selected_sessions = [];
foreach ($sessions as $session) {
    if (isset($session_names[$session])) {
        $selected_sessions[] = $session_names[$session];
    }
}
$sessions_text = !empty($selected_sessions) ? implode(', ', $selected_sessions) : 'None selected';

// Prepare email content
$email_subject = 'New Admissions Application - Sunshine Child-Care Nursery';

$email_body = "NEW ADMISSIONS APPLICATION\n\n";
$email_body .= "================================\n\n";

$email_body .= "PARENT/GUARDIAN INFORMATION\n";
$email_body .= "--------------------------------\n";
$email_body .= "First Name: " . $f_name . "\n";
$email_body .= "Last Name: " . $l_name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Phone: " . $phone . "\n";
$email_body .= "Address: " . $address . "\n\n";

$email_body .= "CHILD INFORMATION\n";
$email_body .= "--------------------------------\n";
$email_body .= "Child's Name: " . $child_name . "\n";
$email_body .= "Child's Age: " . $child_age . "\n";
$email_body .= "Session Preferences: " . $sessions_text . "\n\n";

$email_body .= "CONSENT & PRIVACY\n";
$email_body .= "--------------------------------\n";
$email_body .= "Terms & Conditions Accepted: " . $terms . "\n";
$email_body .= "Privacy Policy Accepted: " . $privacy . "\n";
$email_body .= "Data Use Consent: " . $data_use . "\n\n";

$email_body .= "================================\n";
$email_body .= "Submitted: " . date('Y-m-d H:i:s') . "\n";
$email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";

// Email headers
$headers = [];
$headers[] = 'From: Sunshine Child-Care Nursery <noreply@sunshinechildcareservices.co.uk>';
$headers[] = 'Reply-To: ' . $f_name . ' ' . $l_name . ' <' . $email . '>';
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
        'message' => 'Thank you for your application! We will contact you soon to discuss your child\'s enrollment.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sorry, there was an error submitting your application. Please try again later or contact us directly.'
    ]);
    
    // Log error (optional - for debugging)
    error_log('Admissions form email failed for: ' . implode(', ', $failed_recipients));
}
?>

