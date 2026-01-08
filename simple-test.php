<?php
// Simple test script to verify PHP execution
// Access this directly in browser or via POST

header('Content-Type: application/json');

// Log that we were reached
$log_file = __DIR__ . '/simple-test.log';
$message = date('Y-m-d H:i:s') . " - Script reached via " . $_SERVER['REQUEST_METHOD'] . "\n";
@file_put_contents($log_file, $message, FILE_APPEND);

echo json_encode([
    'status' => 'success',
    'message' => 'PHP is working correctly.',
    'method' => $_SERVER['REQUEST_METHOD'],
    'server_time' => date('Y-m-d H:i:s')
]);
?>
