<?php
session_start();
$dbn = '../database/petcareDB.sqlite';
$db = new PDO($dbn);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id']; // Current logged-in user ID

// Fetch pets
if ($_GET['action'] === 'fetch_pets') {
    $type = $_GET['type'] ?? 'all';
    try {
        $query = $type === 'all' 
            ? 'SELECT * FROM pets WHERE user_id = ?' 
            : 'SELECT * FROM pets WHERE user_id = ? AND type = ?';
        $stmt = $db->prepare($query);
        $type === 'all' ? $stmt->execute([$user_id]) : $stmt->execute([$user_id, $type]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// Save a new pet
if ($_GET['action'] === 'save_pet') {
    $input = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare('INSERT INTO pets (user_id, type, name, age, medical_history) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $input['type'], $input['name'], $input['age'], $input['medical_history']]);
        echo json_encode(["status" => "success", "id" => $db->lastInsertId()]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// Update an existing pet
if ($_GET['action'] === 'save_edits') {
    $input = json_decode(file_get_contents("php://input"), true);
    try {
        $stmt = $db->prepare('UPDATE pets SET type = ?, name = ?, age = ?, medical_history = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$input['type'], $input['name'], $input['age'], $input['medical_history'], $input['id'], $user_id]);
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
        $stmt = $db->prepare('DELETE FROM pets WHERE id = ? AND user_id = ?');
        $stmt->execute([$pet_id, $user_id]);
        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}
?>
