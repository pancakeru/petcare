<?php
session_start();
header('Content-Type: application/json');

// Debug: Log if the session user_id is not set
if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id not set");
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

if ($_GET['action'] === 'save_pet') {
    $input = json_decode(file_get_contents("php://input"), true);

    try {
        // Debug: Log the input received
        error_log("Received input: " . json_encode($input));

        $dbn = '../database/petcareDB.sqlite';
        $db = new PDO("sqlite:" . $dbn);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert pet information
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
        // Debug: Log the error message
        error_log("Error saving pet: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit;
}

// Invalid action fallback
echo json_encode(["success" => false, "message" => "Invalid action"]);
?>
