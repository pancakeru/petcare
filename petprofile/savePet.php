<?php
session_start();
require_once 'petConnect.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// Process the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $type = trim($_POST['type'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $history = trim($_POST['history'] ?? '');

    if (empty($type) || empty($name) || $age <= 0 || empty($history)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required, and age must be a positive number.']);
        exit;
    }

    try {
        // Use the PDO connection from petConnect.php
        $userId = $_SESSION['userId'];

        // Prepare the SQL query
        $sql = "INSERT INTO pets (user_id, type, name, age, medical_history, created_at) 
                VALUES (:user_id, :type, :name, :age, :medical_history, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':medical_history', $history, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            $petId = $conn->lastInsertId();
            echo json_encode(['success' => true, 'message' => 'Pet saved successfully!', 'pet_id' => $petId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the pet.']);
        }
    } catch (PDOException $e) {
        // Handle any database-related errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
