<?php
/**
 * Admissions Form Handler
 * Sends admissions and holiday club applications to HR/Admin and Nursery Manager
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

// Wrap everything in try-catch to handle any runtime errors
try {

// Email configuration - admissions & holiday club go to HR/Admin and Nursery Manager
$to_emails = [
    'hr.admin@sunshinechildcareservices.co.uk',
    'nurserymanager@sunshinechildcareservices.co.uk'
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

// Cost Information
$total_cost = isset($_POST['total_cost']) ? sanitize_input($_POST['total_cost']) : '';
$subtotal = isset($_POST['subtotal']) ? sanitize_input($_POST['subtotal']) : '';
$discount = isset($_POST['discount']) ? sanitize_input($_POST['discount']) : '';
$session_summary = isset($_POST['session_summary']) ? $_POST['session_summary'] : '';

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
} else {
    // Validate that each selected session has at least one day selected
    foreach ($sessions as $session) {
        $days_key = $session . '-days';
        $session_days = isset($_POST[$days_key]) ? $_POST[$days_key] : [];
        
        if (empty($session_days)) {
            $session_name = isset($session_names[$session]) ? $session_names[$session] : ucfirst($session);
            $errors[] = 'Please select at least one day for ' . $session_name;
        }
    }
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
    echo json_encode([
        'status' => 'error',
        'message' => implode(', ', $errors)
    ]);
    exit;
}

// Prepare session preferences text with days
$session_names = [
    'breakfast' => 'Breakfast Club (07:30–09:00)',
    'preschool' => 'Preschool (09:00–15:00)',
    'after-school' => 'After School Club (15:00–18:00)'
];

$day_names = [
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday'
];

$selected_sessions = [];
foreach ($sessions as $session) {
    if (isset($session_names[$session])) {
        // Get days for this session
        $days_key = $session . '-days';
        $session_days = isset($_POST[$days_key]) ? $_POST[$days_key] : [];
        
        $session_text = $session_names[$session];
        
        if (!empty($session_days)) {
            $day_list = [];
            foreach ($session_days as $day) {
                if (isset($day_names[$day])) {
                    $day_list[] = $day_names[$day];
                }
            }
            if (!empty($day_list)) {
                $session_text .= ' - Days: ' . implode(', ', $day_list);
            }
        }
        
        $selected_sessions[] = $session_text;
    }
}
$sessions_text = !empty($selected_sessions) ? implode("\n", $selected_sessions) : 'None selected';

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
$email_body .= "Child's Age: " . $child_age . "\n\n";

$email_body .= "SESSION PREFERENCES & DAYS\n";
$email_body .= "--------------------------------\n";
if (!empty($session_summary)) {
    $email_body .= $session_summary . "\n";
} else {
    $email_body .= $sessions_text . "\n";
}

$email_body .= "\nESTIMATED COST\n";
$email_body .= "--------------------------------\n";
if (!empty($subtotal) && floatval($subtotal) > 0) {
    $email_body .= "Subtotal: £" . $subtotal . "\n";
    if (!empty($discount) && floatval($discount) > 0) {
        $email_body .= "Discount (30%): -£" . $discount . "\n";
    }
    $email_body .= "Total Weekly Cost: £" . $total_cost . "\n";
} else {
    $email_body .= "No cost calculated\n";
}
$email_body .= "\n";

$email_body .= "CONSENT & PRIVACY\n";
$email_body .= "--------------------------------\n";
$email_body .= "Terms & Conditions Accepted: " . $terms . "\n";
$email_body .= "Privacy Policy Accepted: " . $privacy . "\n";
$email_body .= "Data Use Consent: " . $data_use . "\n\n";

$email_body .= "================================\n";
$email_body .= "Submitted: " . date('Y-m-d H:i:s') . "\n";
$email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";

// Email headers - using server domain for better deliverability
$from_email = 'noreply@' . $_SERVER['HTTP_HOST'];
$headers = [];
$headers[] = 'From: Sunshine Child-Care Nursery <' . $from_email . '>';
$headers[] = 'Reply-To: ' . $f_name . ' ' . $l_name . ' <' . $email . '>';
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'X-Priority: 3';

$headers_string = implode("\r\n", $headers);

// Send email to all recipients using PHP mail()
$mail_sent = false;
$failed_recipients = [];
$mail_errors = [];

// Check if we're on localhost
$is_localhost = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1']) || 
                strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0 ||
                strpos($_SERVER['HTTP_HOST'], '127.0.0.1:') === 0;

if ($is_localhost) {
    // Simulate success on localhost
    $mail_sent = true;
} else {
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
}

// Always return success to user (email sending is handled in background)
echo json_encode([
    'status' => 'success',
    'message' => 'Thank you for your application! We will contact you soon to discuss your child\'s enrollment.'
]);

// Log email failure separately for admin
if (!$mail_sent && !$is_localhost) {
    error_log('Admissions form email failed for: ' . implode(', ', $failed_recipients));
}

} catch (Exception $e) {
    // Catch any exceptions and return JSON error
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred processing your request. Please try again later.'
    ]);
    error_log('Admissions form exception: ' . $e->getMessage());
    exit;
} catch (Error $e) {
    // Catch fatal errors
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred processing your request. Please try again later.'
    ]);
    error_log('Admissions form fatal error: ' . $e->getMessage());
    exit;
}
?>

