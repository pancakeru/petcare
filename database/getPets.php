<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

try {
    require 'dbConnect.php'; // Ensures the `pets` table exists

    $stmt = $conn->prepare('SELECT id, type, name, age, history, created_at FROM pets WHERE user_id = :user_id');
    $stmt->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $result = $stmt->execute();

    $pets = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $pets[] = $row;
    }

    echo json_encode(['success' => true, 'pets' => $pets]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
