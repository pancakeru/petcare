<?php
session_start();
require_once 'petConnect.php';

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Validate session username
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Session username not set.']);
    exit;
}
$username = $_SESSION['username'];

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $history = trim($_POST['history'] ?? '');

    // Validate input
    if (empty($type) || empty($name) || $age <= 0 || empty($history)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required, and age must be a positive number.']);
        exit;
    }

    // Insert the pet into the database
    try {
        $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':type', $type, SQLITE3_TEXT);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
        $stmt->bindValue(':history', $history, SQLITE3_TEXT);

        if ($stmt->execute()) {
            $petId = $conn->lastInsertRowID();
            echo json_encode(['success' => true, 'message' => 'Pet saved successfully!', 'pet_id' => $petId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the pet.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
