<?php
include 'petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to perform this action."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM Pets WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pet deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting pet."]);
    }
}
?>
