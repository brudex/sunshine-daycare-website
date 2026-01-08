<?php
/**
 * View Contact Form Submissions
 * Access at: http://localhost:8888/view-submissions.php
 * 
 * SECURITY NOTE: Add password protection before deploying to production!
 */

// Start session first
session_start();

// Simple password protection (change this password!)
$password = 'admin123'; // CHANGE THIS PASSWORD!
$is_authenticated = false;
$error = '';

// Check if password is provided
if (isset($_POST['password'])) {
    if ($_POST['password'] === $password) {
        $_SESSION['authenticated'] = true;
        $is_authenticated = true;
    } else {
        $error = 'Incorrect password';
    }
} elseif (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    $is_authenticated = true;
}

// If authenticated, show logs
if ($is_authenticated || (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true)) {
    $is_authenticated = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contact Form Submissions</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 {
            color: #0e2a47;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ffc100;
        }
        .login-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-form h2 {
            margin-bottom: 20px;
            color: #0e2a47;
        }
        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-form button {
            width: 100%;
            padding: 12px;
            background: #ffc100;
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
        }
        .login-form button:hover {
            background: #FFD700;
        }
        .error {
            color: #d32f2f;
            margin-bottom: 15px;
            padding: 10px;
            background: #ffebee;
            border-radius: 5px;
        }
        .logout {
            float: right;
            padding: 8px 20px;
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .logout:hover {
            background: #e0e0e0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc100;
        }
        .stat-card h3 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }
        .stat-card p {
            font-size: 2rem;
            font-weight: 600;
            color: #0e2a47;
        }
        .submissions {
            margin-top: 30px;
        }
        .submission {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #0e2a47;
        }
        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .submission-date {
            color: #666;
            font-size: 0.9rem;
        }
        .submission-field {
            margin-bottom: 12px;
        }
        .submission-field strong {
            color: #0e2a47;
            display: inline-block;
            min-width: 100px;
        }
        .submission-field span {
            color: #333;
        }
        .no-submissions {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .no-submissions i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        .refresh-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0e2a47;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .refresh-btn:hover {
            background: #1a3d5f;
        }
    </style>
</head>
<body>
    <?php if (!$is_authenticated): ?>
        <div class="login-form">
            <h2>View Submissions</h2>
            <p style="color: #666; margin-bottom: 20px;">Enter password to view contact form submissions</p>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Enter password" required>
                <button type="submit">View Submissions</button>
            </form>
        </div>
    <?php else: ?>
        <div class="container">
            <h1>Contact Form Submissions <a href="?logout=1" class="logout">Logout</a></h1>
            
            <?php
            // Handle logout
            if (isset($_GET['logout'])) {
                $_SESSION['authenticated'] = false;
                session_destroy();
                header('Location: view-submissions.php');
                exit;
            }
            
            $log_file = __DIR__ . '/contact-form-submissions.log';
            $submissions = [];
            
            if (file_exists($log_file)) {
                $content = file_get_contents($log_file);
                // Split by separator
                $entries = explode('----------------------------------------', $content);
                
                foreach ($entries as $entry) {
                    if (trim($entry)) {
                        $lines = explode("\n", trim($entry));
                        $submission = [];
                        foreach ($lines as $line) {
                            if (strpos($line, ':') !== false) {
                                list($key, $value) = explode(':', $line, 2);
                                $submission[trim($key)] = trim($value);
                            }
                        }
                        if (!empty($submission)) {
                            $submissions[] = $submission;
                        }
                    }
                }
                
                // Reverse to show newest first
                $submissions = array_reverse($submissions);
            }
            
            $total_submissions = count($submissions);
            $today_count = 0;
            $today = date('Y-m-d');
            
            foreach ($submissions as $sub) {
                if (isset($sub['Submitted']) && strpos($sub['Submitted'], $today) === 0) {
                    $today_count++;
                }
            }
            ?>
            
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Submissions</h3>
                    <p><?php echo $total_submissions; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Today's Submissions</h3>
                    <p><?php echo $today_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Last Submission</h3>
                    <p style="font-size: 1rem;">
                        <?php 
                        if (!empty($submissions)) {
                            echo isset($submissions[0]['Submitted']) ? date('M d, Y H:i', strtotime($submissions[0]['Submitted'])) : 'N/A';
                        } else {
                            echo 'None';
                        }
                        ?>
                    </p>
                </div>
            </div>
            
            <a href="view-submissions.php" class="refresh-btn">🔄 Refresh</a>
            
            <div class="submissions">
                <?php if (empty($submissions)): ?>
                    <div class="no-submissions">
                        <div style="font-size: 4rem; color: #ddd; margin-bottom: 20px;">📭</div>
                        <h2>No submissions yet</h2>
                        <p>Form submissions will appear here once someone submits the contact form.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($submissions as $index => $sub): ?>
                        <div class="submission">
                            <div class="submission-header">
                                <h3>Submission #<?php echo $total_submissions - $index; ?></h3>
                                <span class="submission-date">
                                    <?php echo isset($sub['Submitted']) ? date('F d, Y at g:i A', strtotime($sub['Submitted'])) : 'Date unknown'; ?>
                                </span>
                            </div>
                            <div class="submission-field">
                                <strong>Name:</strong> 
                                <span><?php echo isset($sub['Name']) ? htmlspecialchars($sub['Name']) : 'N/A'; ?></span>
                            </div>
                            <div class="submission-field">
                                <strong>Email:</strong> 
                                <span><?php echo isset($sub['Email']) ? htmlspecialchars($sub['Email']) : 'N/A'; ?></span>
                            </div>
                            <div class="submission-field">
                                <strong>Phone:</strong> 
                                <span><?php echo isset($sub['Phone']) ? htmlspecialchars($sub['Phone']) : 'Not provided'; ?></span>
                            </div>
                            <div class="submission-field">
                                <strong>Subject:</strong> 
                                <span><?php echo isset($sub['Subject']) ? htmlspecialchars($sub['Subject']) : 'N/A'; ?></span>
                            </div>
                            <div class="submission-field">
                                <strong>Message:</strong> 
                                <div style="margin-top: 8px; padding: 15px; background: white; border-radius: 5px; white-space: pre-wrap;"><?php echo isset($sub['Message']) ? htmlspecialchars($sub['Message']) : 'N/A'; ?></div>
                            </div>
                            <div class="submission-field" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0; font-size: 0.85rem; color: #666;">
                                <strong>IP Address:</strong> <?php echo isset($sub['IP']) ? htmlspecialchars($sub['IP']) : 'N/A'; ?> | 
                                <strong>Email Sent:</strong> <?php echo isset($sub['Email Sent']) ? htmlspecialchars($sub['Email Sent']) : 'Unknown'; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>

