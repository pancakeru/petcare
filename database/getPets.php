<?php
session_start();
require_once '../database/dbConnect.php';

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT type, name, age, history FROM pets WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$pets = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $pets[] = $row;
}

echo json_encode($pets);
?>
