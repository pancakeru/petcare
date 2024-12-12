<?php
include '../database/dbConnect.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$username = $_SESSION['username'];
$type = $_POST['type'] ?? '';
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$history = $_POST['history'] ?? '';

// Validate input
if (empty($type) || empty($name) || empty($age) || empty($history)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

try {
    $sql = "INSERT INTO Pets (username, type, name, age, history) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$username, $type, $name, $age, $history])) {
        echo json_encode(['success' => true, 'pet_id' => $pdo->lastInsertId(), 'message' => 'Pet saved successfully.']);
    } else {
        error_log("SQL Error: " . implode(", ", $stmt->errorInfo()));
        echo json_encode(['success' => false, 'message' => 'Error saving pet.']);
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
}
?>
