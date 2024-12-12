<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['type'], $data['name'], $data['age'], $data['history'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

try {
    $db = new PDO('sqlite:petcareDB.sqlite');
    $stmt = $db->prepare('INSERT INTO pets (user_id, type, name, age, history) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $_SESSION['user_id'],
        $data['type'],
        $data['name'],
        $data['age'],
        $data['history'],
    ]);
    file_put_contents('debug_log.txt', "Pet saved: " . print_r($data, true), FILE_APPEND);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
