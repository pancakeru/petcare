<?php
include '../database/petConnect.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure JSON response

// Check if user is logged in
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

if (!is_numeric($age) || (int)$age <= 0) {
    echo json_encode(['success' => false, 'message' => 'Age must be a positive number.']);
    exit;
}

try {
    // Prepare SQL statement
    $sql = "INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':history', $history, PDO::PARAM_STR);

    // Execute query
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'pet_id' => $pdo->lastInsertId(),
            'message' => 'Pet saved successfully.',
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: Unable to save pet.']);
    }
} catch (PDOException $e) {
    // Handle exceptions
    error_log("PDOException: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
}
?>
