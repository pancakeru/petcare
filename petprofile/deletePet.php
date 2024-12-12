<?php
include '../database/petConnect.php';
session_start();

header('Content-Type: application/json'); // Ensure JSON response

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$username = $_SESSION['username'];
$pet_id = $_POST['pet_id'] ?? '';

// Validate input
if (empty($pet_id)) {
    echo json_encode(['success' => false, 'message' => 'Pet ID is required.']);
    exit;
}

try {
    // Prepare SQL statement
    $sql = "DELETE FROM Pets WHERE id = :pet_id AND username = :username";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':pet_id', $pet_id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pet deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: Unable to delete pet.']);
    }
} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
}
?>
