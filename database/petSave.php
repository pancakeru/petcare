<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
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

    $stmt = $db->prepare('INSERT INTO pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)');
    $stmt->execute([
        ':username' => $_SESSION['username'],
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
