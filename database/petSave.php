<?php
session_start();
require_once '../database/dbConnect.php';

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['type'], $data['name'], $data['age'], $data['history'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

$type = $data['type'];
$name = $data['name'];
$age = (int)$data['age'];
$history = $data['history'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO pets (user_id, type, name, age, history) VALUES (:user_id, :type, :name, :age, :history)";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$stmt->bindValue(':type', $type, SQLITE3_TEXT);
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':age', $age, SQLITE3_INTEGER);
$stmt->bindValue(':history', $history, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Pet added successfully']);
} else {
    echo json_encode(['error' => 'Failed to add pet']);
}
?>
