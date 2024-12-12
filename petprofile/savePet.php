<?php
include '../database/petConnect.php';
$db = petConnect();

$type = $_POST['type'];
$name = $_POST['name'];
$age = $_POST['age'];
$history = $_POST['history'];

$stmt = $db->prepare("INSERT INTO pets (type, name, age, history) VALUES (?, ?, ?, ?)");
$stmt->execute([$type, $name, $age, $history]);
echo json_encode(["success" => true]);
?>
