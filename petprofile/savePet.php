<?php
include 'petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to perform this action."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $history = $_POST['history'];

    $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
    $stmt->bindValue(':history', $history, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pet added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding pet."]);
    }
}
?>
