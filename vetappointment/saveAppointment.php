<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validate inputs
if (!$data || !isset($data['petId'], $data['date'], $data['time'], $data['reason'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit;
}

try {
    $db = new PDO('sqlite:vetappointments.sqlite');
    $stmt = $db->prepare('INSERT INTO appointments (user_id, pet_id, date, time, reason) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $_SESSION['user_id'],
        $data['petId'], // Use pet ID
        $data['date'],
        $data['time'],
        $data['reason'],
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
