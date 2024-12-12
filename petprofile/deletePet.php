<?php
include '../database/dbConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$pet_id = $_POST['pet_id'];

$sql = "DELETE FROM Pets WHERE id = ? AND username = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$pet_id, $_SESSION['username']])) {
    echo json_encode(['success' => true, 'message' => 'Pet deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting pet.']);
}
?>
