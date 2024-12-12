<?php
require_once 'dbConnect.php'; // Ensure this file correctly connects to petcareDB.sqlite
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'] ?? '';
$name = $data['name'] ?? '';
$age = $data['age'] ?? '';
$history = $data['history'] ?? '';
$username = $_SESSION['username'];

if (!$type || !$name || !$age || !$history) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

try {
    $conn = new PDO("sqlite:../database/petcareDB.sqlite");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':type', $type, PDO::PARAM_STR);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':age', $age, PDO::PARAM_INT);
    $stmt->bindValue(':history', $history, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save pet data.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
