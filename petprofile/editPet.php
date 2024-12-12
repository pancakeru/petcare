<?php
session_start();
require_once 'petConnect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'] ?? null;
    $petId = intval($_POST['pet_id'] ?? 0);
    $type = trim($_POST['type'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $history = trim($_POST['history'] ?? '');

    if (empty($username) || $petId <= 0 || empty($type) || empty($name) || $age <= 0 || empty($history)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required, and age must be a positive number.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE Pets SET type = :type, name = :name, age = :age, history = :history WHERE id = :pet_id AND username = :username");
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);
    $stmt->bindValue(':pet_id', $petId, SQLITE3_INTEGER);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);

    if ($stmt->execute() && $conn->changes() > 0) {
        echo json_encode(['success' => true, 'message' => 'Pet updated successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the pet or pet not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
