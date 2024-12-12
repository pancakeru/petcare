<?php
session_start();
require_once 'petConnect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = trim($_POST['type']);
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $history = trim($_POST['history']);

    if (empty($type) || empty($name) || $age <= 0 || empty($history)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required, and age must be a positive number.']);
        exit;
    }

    $userId = $_SESSION['userId'];

    $sql = "INSERT INTO pets (user_id, type, name, age, medical_history, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issis", $userId, $type, $name, $age, $history);

        if ($stmt->execute()) {
            $petId = $stmt->insert_id;
            echo json_encode(['success' => true, 'message' => 'Pet saved successfully!', 'pet_id' => $petId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving pet.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
