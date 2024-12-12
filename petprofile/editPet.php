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
$type = $_POST['type'] ?? '';
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$history = $_POST['history'] ?? '';

// Validate input
if (empty($pet_id) || empty($type) || empty($name) || empty($age) || empty($history)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!is_numeric($age) || (int)$age <= 0) {
    echo json_encode(['success' => false, 'message' => 'Age must be a positive number.']);
    exit;
}

try {
    // Prepare SQL statement
    $sql = "UPDATE Pets SET type = :type, name = :name, age = :age, history = :history WHERE id = :pet_id AND username = :username";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':history', $history, PDO::PARAM_STR);
    $stmt->bindParam(':pet_id', $pet_id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    // Execute query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pet updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: Unable to update pet.']);
    }
} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Internal server error.']);
}
?>
