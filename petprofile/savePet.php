<?php
session_start();
header('Content-Type: application/json');
include '../database/petConnect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'];
$name = $data['name'];
$age = $data['age'];
$history = $data['history'];

try {
    $stmt = $db->prepare("INSERT INTO pets (user_id, type, name, age, history) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $type, $name, $age, $history]);
    $id = $db->lastInsertId();
    echo json_encode(["success" => true, "id" => $id]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
