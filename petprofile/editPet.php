<?php
include '../database/dbConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$pet_id = $_POST['pet_id'];
$type = $_POST['type'];
$name = $_POST['name'];
$age = $_POST['age'];
$history = $_POST['history'];

$sql = "UPDATE Pets SET type = ?, name = ?, age = ?, history = ? WHERE id = ? AND username = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$type, $name, $age, $history, $pet_id, $_SESSION['username']])) {
    echo json_encode(['success' => true, 'message' => 'Pet updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating pet.']);
}
?>
