<?php
session_start();
header('Content-Type: application/json');

$response = [
    'loggedIn' => isset($_SESSION['username']) && !empty($_SESSION['username']) // Check for username
];

echo json_encode($response);
?>
