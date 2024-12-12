<?php
include '../database/petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to perform this action."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $history = $_POST['history'];

    $stmt = $conn->prepare("UPDATE Pets SET type = :type, name = :name, age = :age, history = :history WHERE id = :id");
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pet updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating pet."]);
    }
}
?>
