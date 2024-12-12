<?php
session_start();
require 'petConnect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $petId = $_POST['pet_id'];
        $type = $_POST['type'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $history = $_POST['history'];

        $sql = "UPDATE pets SET type = ?, name = ?, age = ?, medical_history = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $type, $name, $age, $history, $petId, $userId);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Pet profile updated successfully!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error updating pet profile."
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "You must log in to edit a pet."
        ]);
    }
}
?>
