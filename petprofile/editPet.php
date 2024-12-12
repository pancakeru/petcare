<?php
require_once '../database/dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petId = $_POST['pet_id'] ?? null;
    $type = $_POST['type'] ?? '';
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $history = $_POST['history'] ?? '';

    if (!$petId || !$type || !$name || !$age || !$history) {
        echo "Error: All fields are required.";
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE Pets SET type = :type, name = :name, age = :age, history = :history WHERE id = :id");
        $stmt->bindValue(':type', $type, SQLITE3_TEXT);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':age', $age, SQLITE3_INTEGER);
        $stmt->bindValue(':history', $history, SQLITE3_TEXT);
        $stmt->bindValue(':id', $petId, SQLITE3_INTEGER);
        $stmt->execute();
        echo "Success: Pet updated successfully.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>
