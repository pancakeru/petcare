<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in',
        'session' => $_SESSION, // Debugging
        'cookies' => $_COOKIE   // Debugging
    ]);
    exit();
}

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['type'], $data['name'], $data['age'], $data['medical_history'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

try {
    // Database connection
    $db = new PDO('sqlite:../database/petcareDB.sqlite'); // Adjust the path
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert pet data into the database
    $stmt = $db->prepare('INSERT INTO pets (user_id, type, name, age, medical_history) VALUES (:user_id, :type, :name, :age, :medical_history)');
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':type' => $data['type'],
        ':name' => $data['name'],
        ':age' => $data['age'],
        ':medical_history' => $data['medical_history']
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>
