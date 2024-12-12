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

    if (empty($username) || $petId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid pet ID or user not logged in.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM Pets WHERE id = :pet_id AND username = :username");
    $stmt->bindValue(':pet_id', $petId, SQLITE3_INTEGER);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);

    if ($stmt->execute() && $conn->changes() > 0) {
        echo json_encode(['success' => true, 'message' => 'Pet deleted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the pet or pet not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
