<?php
session_start();
header('Content-Type: application/json');
include '../database/petConnect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$type = $data['type'];
$name = $data['name'];
$age = $data['age'];
$history = $data['history'];

try {
    $stmt = $db->prepare("UPDATE pets SET type = ?, name = ?, age = ?, history = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$type, $name, $age, $history, $id, $_SESSION['user_id']]);
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
