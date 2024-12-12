<?php
header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:../database/petcareDB.sqlite');
    $stmt = $db->prepare('SELECT type, name, age, history, created_at FROM pets');
    $stmt->execute();
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'pets' => $pets]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
