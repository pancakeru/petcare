<?php
session_start();
require 'petConnect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $type = $_POST['type'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $history = $_POST['history'];

        $sql = "INSERT INTO pets (user_id, type, name, age, medical_history) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issis", $userId, $type, $name, $age, $history);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Pet profile added successfully!",
                "pet_id" => $stmt->insert_id
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error adding pet profile."
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "You must log in to add a pet."
        ]);
    }
}
?>
