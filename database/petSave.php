<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'dbConnect.php'; // Include database connection

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['type'], $data['name'], $data['age'], $data['history'])) {
    $type = $data['type'];
    $name = $data['name'];
    $age = intval($data['age']);
    $history = $data['history'];

    $query = "INSERT INTO pets (type, name, age, medical_history) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssis", $type, $name, $age, $history);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
}

$conn->close();
?>
