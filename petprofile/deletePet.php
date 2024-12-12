<?php
include '../database/petConnect.php';
$db = petConnect();

$id = $_POST['id'];

$stmt = $db->prepare("DELETE FROM pets WHERE id = ?");
$stmt->execute([$id]);
echo json_encode(["success" => true]);
?>
