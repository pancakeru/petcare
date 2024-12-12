<?php
require_once 'dbConnect.php'; // This connects to the SQLite database

session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Parse incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Extract fields
$type = $data['type'] ?? '';
$name = $data['name'] ?? '';
$age = $data['age'] ?? '';
$history = $data['history'] ?? '';
$username = $_SESSION['username'];

// Validate fields
if (!$type || !$name || !$age || !$history) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

// Ensure the `pets` table exists
$sql_create_table = "CREATE TABLE IF NOT EXISTS pets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    type TEXT NOT NULL,
    name TEXT NOT NULL,
    age INTEGER NOT NULL,
    history TEXT NOT NULL
)";
if (!$conn->exec($sql_create_table)) {
    echo json_encode(['success' => false, 'message' => 'Error creating table: ' . $conn->lastErrorMsg()]);
    exit();
}

// Insert pet data
try {
    $sql = "INSERT INTO pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save pet data.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
