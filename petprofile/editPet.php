<?php
include '../database/petConnect.php';
$db = petConnect();

$id = $_POST['id'];
$type = $_POST['type'];
$name = $_POST['name'];
$age = $_POST['age'];
$history = $_POST['history'];

$stmt = $db->prepare("UPDATE pets SET type = ?, name = ?, age = ?, history = ? WHERE id = ?");
$stmt->execute([$type, $name, $age, $history, $id]);
echo json_encode(["success" => true]);
?>
