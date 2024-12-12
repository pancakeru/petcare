<?php
// Include database connection
include '../database/petConnect.php';
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Clear output buffer and set response to JSON
ob_clean();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Retrieve POST data
$username = $_SESSION['username'] ?? null;
$type = trim($_POST['type'] ?? '');
$name = trim($_POST['name'] ?? '');
$age = trim($_POST['age'] ?? '');
$history = trim($_POST['history'] ?? '');

// Validate input data
if (!$type || !$name || !$age || !$history) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!ctype_digit($age) || (int)$age <= 0) {
    echo json_encode(['success' => false, 'message' => 'Age must be a positive number.']);
    exit;
}

try {
    // Prepare the SQL statement
    $sql = "INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':history', $history, PDO::PARAM_STR);

    // Execute the statement
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
    // Log the error for debugging and return a generic error message
    error_log("PDOException: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
    exit;
}
?>
