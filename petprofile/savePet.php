<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
header('Content-Type: application/json');

include '../database/petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    try {
        // Retrieve and validate input
        $username = $_SESSION['username'];
        $type = $_POST['type'] ?? '';
        $name = $_POST['name'] ?? '';
        $age = $_POST['age'] ?? '';
        $history = $_POST['history'] ?? '';

        if (empty($type) || empty($name) || !is_numeric($age) || (int)$age <= 0 || empty($history)) {
            throw new Exception("Invalid input data. Please check all fields.");
        }

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':type', $type, SQLITE3_TEXT);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':age', (int)$age, SQLITE3_INTEGER);
        $stmt->bindValue(':history', $history, SQLITE3_TEXT);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Pet saved successfully."]);
        } else {
            throw new Exception("Failed to save the pet. Please try again.");
        }
    } catch (Exception $e) {
        // Return error message in JSON
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    // Invalid request method
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
