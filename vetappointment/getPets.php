<?php
header('Content-Type: application/json');
include '../database/petConnect.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to fetch pets."]);
    exit;
}

try {
    $username = $_SESSION['username'];

    // Query to fetch pets
    $stmt = $conn->prepare("SELECT id, type, name, age, history FROM Pets WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    $pets = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $pets[] = $row;
    }

    echo json_encode(["success" => true, "pets" => $pets]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
