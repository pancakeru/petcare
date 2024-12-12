<?php
require_once 'dbConnect.php';
session_start();
header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Parse incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'] ?? '';
$name = $data['name'] ?? '';
age = $data['age'] ?? null;
history = $data['history'] ?? '';

if (!$type || !$name || is_null($age) || !$history) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

try {
    // Ensure the pets table exists
    $sql = "CREATE TABLE IF NOT EXISTS pets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        type TEXT NOT NULL,
        name TEXT NOT NULL,
        age INTEGER NOT NULL,
        medical_history TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    $conn->exec($sql);

    // Insert pet data
    $stmt = $conn->prepare("INSERT INTO pets (user_id, type, name, age, medical_history) VALUES (:user_id, :type, :name, :age, :history)");
    $stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pet saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save pet data.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
