<?php
include '../database/dbConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$username = $_SESSION['username'];
$type = $_POST['type'];
$name = $_POST['name'];
$age = $_POST['age'];
$history = $_POST['history'];

$sql = "INSERT INTO Pets (username, type, name, age, history) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$username, $type, $name, $age, $history])) {
    echo json_encode(['success' => true, 'pet_id' => $pdo->lastInsertId(), 'message' => 'Pet saved successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving pet.']);
}
?>
