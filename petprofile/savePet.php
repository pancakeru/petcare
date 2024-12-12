<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Suppress unexpected output
ob_start();
header('Content-Type: application/json');

// Include database connection
include '../database/petConnect.php';
session_start();

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    ob_end_flush();
    exit;
}

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to perform this action."]);
    ob_end_flush();
    exit;
}

try {
    // Retrieve and validate input
    $username = $_SESSION['username'];
    $type = $_POST['type'] ?? '';
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? 0;
    $history = $_POST['history'] ?? '';

    if (empty($type) || empty($name) || !is_numeric($age) || (int)$age <= 0 || empty($history)) {
        throw new Exception("Invalid input data. Please check all fields.");
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', (int)$age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pet saved successfully."]);
    } else {
        throw new Exception("Failed to save the pet. Please try again.");
    }
} catch (Exception $e) {
    // Handle exceptions and return error response
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

// Flush the output buffer and send the response
ob_end_flush();
?>
