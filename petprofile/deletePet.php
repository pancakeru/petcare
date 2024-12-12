<?php
require_once '../database/dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petId = $_POST['pet_id'] ?? null;

    if (!$petId) {
        echo "Error: Missing pet ID.";
        exit();
    }

    try {
        $stmt = $conn->prepare("DELETE FROM Pets WHERE id = :id");
        $stmt->bindValue(':id', $petId, SQLITE3_INTEGER);
        $stmt->execute();
        echo "Success: Pet deleted successfully.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>
