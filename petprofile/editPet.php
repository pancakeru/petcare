<?php
include 'dbConnect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$pet_id = $_POST['pet_id'];
$type = $_POST['type'];
$name = $_POST['name'];
$age = $_POST['age'];
$history = $_POST['history'];

$sql = "UPDATE Pets SET type = ?, name = ?, age = ?, history = ? WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$type, $name, $age, $history, $pet_id, $_SESSION['user_id']])) {
    echo json_encode(['success' => true, 'message' => 'Pet updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating pet.']);
}
?>
