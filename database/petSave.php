<?php
require_once 'dbConnect.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$name = $data['name'];
$age = $data['age'];
$history = $data['history'];
$username = $_SESSION['username'];

$sql = "INSERT INTO pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':username', $username);
$stmt->bindValue(':type', $type);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':age', $age);
$stmt->bindValue(':history', $history);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save pet data.']);
}
?>
