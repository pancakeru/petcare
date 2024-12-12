<?php
session_start();
require '../database/petConnect.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $petId = $_POST['pet_id'];

        $sql = "DELETE FROM pets WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $petId, $userId);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Pet profile deleted successfully!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error deleting pet profile."
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "You must log in to delete a pet."
        ]);
    }
}
?>
