// Create testSession.php
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Example user_id
    echo "Session started. User ID set to 1.";
} else {
    echo "Session exists. User ID is " . $_SESSION['user_id'];
}
?>


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
