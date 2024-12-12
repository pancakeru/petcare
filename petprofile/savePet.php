<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure JSON header

try {
    require 'petConnect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_POST['type'] ?? '';
        $name = $_POST['name'] ?? '';
        $age = $_POST['age'] ?? '';
        $history = $_POST['history'] ?? '';

        if (empty($type) || empty($name) || empty($age) || empty($history)) {
            throw new Exception('All fields are required.');
        }

        $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
        $stmt->bindValue(':username', 'testuser'); // Replace with the logged-in username
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':age', $age);
        $stmt->bindValue(':history', $history);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Pet saved successfully!', 'pet_id' => $conn->lastInsertRowID()]);
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
