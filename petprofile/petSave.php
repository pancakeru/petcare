<?php
header('Content-Type: application/json');
require_once '../database/dbConnect.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $petName = $_POST['petName'];
        $petAge = $_POST['petAge'];
        $petType = $_POST['petSelect'];
        $medicalHistory = $_POST['medicalHistory'];
        $userId = 1; // Assuming a logged-in user with ID 1 for this example

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO pets (user_id, type, name, age, history) VALUES (:user_id, :type, :name, :age, :history)");
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':type', $petType, SQLITE3_TEXT);
        $stmt->bindValue(':name', $petName, SQLITE3_TEXT);
        $stmt->bindValue(':age', $petAge, SQLITE3_INTEGER);
        $stmt->bindValue(':history', $medicalHistory, SQLITE3_TEXT);
        $stmt->execute();

        echo json_encode(['success' => true]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
