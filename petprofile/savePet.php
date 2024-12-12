<?php
require_once '../database/dbConnect.php';
session_start();

header('Content-Type: application/json'); // Set correct header
ob_start(); // Start output buffering

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'You must log in to add a pet.']);
    exit();
}

$username = $_SESSION['username'] ?? '';
$type = $_POST['type'] ?? '';
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$history = $_POST['history'] ?? '';

try {
    // Validate inputs
    if (empty($type) || empty($name) || empty($age) || empty($history)) {
        throw new Exception("All fields are required.");
    }

    if (!is_numeric($age) || $age <= 0) {
        throw new Exception("Age must be a positive number.");
    }

    // Insert pet data
    $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);
    $stmt->execute();

    $lastId = $conn->lastInsertRowID();
    ob_end_clean(); // Clear any buffered output
    echo json_encode([
        'success' => true,
        'message' => 'Pet added successfully!',
        'pet_id' => $lastId,
    ]);
} catch (Exception $e) {
    ob_end_clean(); // Clear any buffered output
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
