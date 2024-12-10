<?php
$dbn = 'petcareDB.sqlite';
$db = new PDO($dbn);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_GET['action'] === 'fetch_pets') {
    $type = $_GET['type'] ?? 'all';
    if ($type === 'all') {
        $stmt = $db->query('SELECT * FROM pets');
    } else {
        $stmt = $db->prepare('SELECT * FROM pets WHERE type = ?');
        $stmt->execute([$type]);
    }
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pets);
    exit;
}

// Save a new pet
if ($_GET['action'] === 'save_pet') {
    $input = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare('INSERT INTO pets (type, name, age, medical_history) VALUES (?, ?, ?, ?)');
    $stmt->execute([$input['type'], $input['name'], $input['age'], $input['medical_history']]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Save a new appointment
if ($_GET['action'] === 'save_appointment') {
    $input = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare('INSERT INTO appointments (pet_id, date, time, reason) VALUES (?, ?, ?, ?)');
    $stmt->execute([$input['pet_id'], $input['date'], $input['time'], $input['reason']]);
    echo json_encode(["status" => "success"]);
    exit;
}
?>
