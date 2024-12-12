<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'petConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $type = $_POST['type'] ?? '';
        $name = $_POST['name'] ?? '';
        $age = $_POST['age'] ?? 0;
        $history = $_POST['history'] ?? '';

        if (empty($type) || empty($name) || empty($age) || empty($history)) {
            throw new Exception("All fields are required.");
        }

        $stmt = $conn->prepare("INSERT INTO Pets (username, type, name, age, history) VALUES (:username, :type, :name, :age, :history)");
        $stmt->bindValue(':username', 'testuser'); // Replace with the logged-in user
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':age', $age);
        $stmt->bindValue(':history', $history);
        $stmt->execute();

        $response = ['success' => true, 'message' => 'Pet saved successfully!', 'pet_id' => $conn->lastInsertRowID()];
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
