<?php
/**
 * Email Test Script
 * Use this to test if PHP mail() function works on your server
 */

// Configuration
$test_email = 'mbaah80@gmail.com'; // Change this to your email

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Test - PHP mail() Function</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #4CAF50;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .error {
            color: #f44336;
            font-weight: bold;
        }
        .info {
            color: #2196F3;
            font-weight: bold;
        }
        .warning {
            color: #ff9800;
            font-weight: bold;
        }
        pre {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP mail() Function Test</h1>
        
        <?php
        // Test 1: Check if mail() function exists
        echo '<div class="test-section">';
        echo '<h2>Test 1: mail() Function Availability</h2>';
        if (function_exists('mail')) {
            echo '<p class="success">✓ mail() function is available</p>';
        } else {
            echo '<p class="error">✗ mail() function is NOT available</p>';
            echo '<p>Your server does not have PHP mail() function enabled.</p>';
        }
        echo '</div>';
        
        // Test 2: Check server environment
        echo '<div class="test-section">';
        echo '<h2>Test 2: Server Environment</h2>';
        echo '<p><strong>Server:</strong> ' . $_SERVER['HTTP_HOST'] . '</p>';
        echo '<p><strong>PHP Version:</strong> ' . phpversion() . '</p>';
        
        $is_localhost = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1']) || 
                        strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0 ||
                        strpos($_SERVER['HTTP_HOST'], '127.0.0.1:') === 0;
        
        if ($is_localhost) {
            echo '<p class="warning">⚠ Running on localhost</p>';
            echo '<p>PHP mail() typically does not work on localhost unless you configure a mail server (sendmail, postfix, etc.).</p>';
        } else {
            echo '<p class="info">ℹ Running on production server</p>';
            echo '<p>PHP mail() should work if your server has mail configuration.</p>';
        }
        echo '</div>';
        
        // Test 3: Check sendmail path (if available)
        echo '<div class="test-section">';
        echo '<h2>Test 3: Mail Configuration</h2>';
        $sendmail_path = ini_get('sendmail_path');
        if ($sendmail_path) {
            echo '<p class="success">✓ sendmail_path is configured</p>';
            echo '<p><strong>Path:</strong> ' . htmlspecialchars($sendmail_path) . '</p>';
        } else {
            echo '<p class="warning">⚠ sendmail_path is not configured</p>';
            echo '<p>This may prevent emails from sending. Contact your server administrator.</p>';
        }
        echo '</div>';
        
        // Test 4: Send test email
        if (isset($_POST['send_test_email']) && function_exists('mail')) {
            echo '<div class="test-section">';
            echo '<h2>Test 4: Sending Test Email</h2>';
            
            $to = $test_email;
            $subject = 'Test Email from PHP mail() - ' . date('Y-m-d H:i:s');
            $message = "This is a test email sent from PHP mail() function.\n\n";
            $message .= "Server: " . $_SERVER['HTTP_HOST'] . "\n";
            $message .= "Time: " . date('Y-m-d H:i:s') . "\n";
            $message .= "PHP Version: " . phpversion() . "\n\n";
            $message .= "If you received this email, PHP mail() is working correctly!";
            
            $headers = [];
            $headers[] = 'From: Sunshine Child-Care Nursery <noreply@sunshinechildcareservices.co.uk>';
            $headers[] = 'X-Mailer: PHP/' . phpversion();
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            
            $headers_string = implode("\r\n", $headers);
            
            // Clear any previous errors
            error_clear_last();
            
            // Attempt to send
            $result = mail($to, $subject, $message, $headers_string);
            
            if ($result) {
                echo '<p class="success">✓ mail() function returned TRUE</p>';
                echo '<p><strong>Test email sent to:</strong> ' . htmlspecialchars($test_email) . '</p>';
                
                if ($is_localhost) {
                    echo '<p class="warning">⚠ Note: On localhost, mail() may return TRUE but the email may not actually be sent.</p>';
                    echo '<p>Check your inbox and spam folder. If you don\'t receive it, you need to configure a mail server.</p>';
                } else {
                    echo '<p class="info">ℹ Check your inbox (and spam folder) for the test email.</p>';
                }
            } else {
                echo '<p class="error">✗ mail() function returned FALSE</p>';
                $last_error = error_get_last();
                if ($last_error) {
                    echo '<p><strong>Error:</strong> ' . htmlspecialchars($last_error['message']) . '</p>';
                }
                echo '<p>Email was not sent. Check your server mail configuration.</p>';
            }
            echo '</div>';
        } else {
            echo '<div class="test-section">';
            echo '<h2>Test 4: Send Test Email</h2>';
            echo '<p>Click the button below to send a test email to: <strong>' . htmlspecialchars($test_email) . '</strong></p>';
            echo '<form method="POST">';
            echo '<button type="submit" name="send_test_email" value="1">Send Test Email</button>';
            echo '</form>';
            echo '</div>';
        }
        ?>
        
        <div class="test-section">
            <h2>Summary</h2>
            <ul>
                <li>All form submissions are <strong>logged</strong> to <code>contact-form-submissions.log</code> regardless of email status</li>
                <li>View submissions at: <a href="view-submissions.php">view-submissions.php</a></li>
                <li>On <strong>localhost</strong>: Emails won't send unless you configure a mail server</li>
                <li>On <strong>production</strong>: Emails should work if your hosting provider has mail() configured</li>
            </ul>
        </div>
    </div>
</body>
</html>

