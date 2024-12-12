<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['type'], $data['name'], $data['age'], $data['history'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

try {
    $db = new PDO('sqlite:database/petcareDB.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare('INSERT INTO pets (user_id, type, name, age, history) VALUES (:user_id, :type, :name, :age, :history)');
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':type' => $data['type'],
        ':name' => $data['name'],
        ':age' => $data['age'],
        ':history' => $data['history']
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
