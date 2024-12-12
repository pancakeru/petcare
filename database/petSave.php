<?php
session_start();

$dbn = '../database/petcareDB.sqlite';
$db = new PDO("sqlite:" . $dbn);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_GET['action'] === 'save_pet') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "User not logged in"]);
        exit;
    }

    try {
        $stmt = $db->prepare('INSERT INTO pets (user_id, type, name, age, medical_history) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $_SESSION['user_id'],
            $input['type'],
            $input['name'],
            $input['age'],
            $input['medical_history']
        ]);
        $petId = $db->lastInsertId(); // Get the inserted pet ID
        echo json_encode(["success" => true, "id" => $petId]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// Ensure invalid action is caught
echo json_encode(["success" => false, "message" => "Invalid action"]);
?>
