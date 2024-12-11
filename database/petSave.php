<?php
$dbn = '../database/petcareDB.sqlite';
$db = new SQLite3($dbn);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch all pets
if ($_GET['action'] === 'fetch_pets') {
    $type = $_GET['type'] ?? 'all';
    try {
        if ($type === 'all') {
            $stmt = $db->query('SELECT * FROM pets');
        } else {
            $stmt = $db->prepare('SELECT * FROM pets WHERE type = ?');
            $stmt->execute([$type]);
        }
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($pets);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// Save a new pet
if ($_GET['action'] === 'save_pet') {
    $input = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare('INSERT INTO pets (type, name, age, medical_history) VALUES (?, ?, ?, ?)');
        $stmt->execute([$input['type'], $input['name'], $input['age'], $input['medical_history']]);
        $petId = $db->lastInsertId(); // Fetch the new pet's ID
        echo json_encode(["status" => "success", "id" => $petId]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// Update an existing pet
if ($_GET['action'] === 'save_edits') {
    $input = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare('UPDATE pets SET type = ?, name = ?, age = ?, medical_history = ? WHERE id = ?');
        $stmt->execute([$input['type'], $input['name'], $input['age'], $input['medical_history'], $input['id']]);
        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// Delete a pet
if ($_GET['action'] === 'delete_pet') {
    $pet_id = $_GET['id'] ?? null;
    try {
        $stmt = $db->prepare('DELETE FROM pets WHERE id = ?');
        $stmt->execute([$pet_id]);
        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// Invalid action
echo json_encode(["status" => "error", "message" => "Invalid action"]);
?>
