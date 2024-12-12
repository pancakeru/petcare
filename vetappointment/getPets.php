<?php
header('Content-Type: application/json');
include '../database/petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to view pets."]);
    exit;
}

try {
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT * FROM Pets WHERE username = :username ORDER BY created_at DESC");
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
