<?php
header('Content-Type: application/json');
include '../database/petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to view appointments."]);
    exit;
}

try {
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT a.id, a.date, a.time, a.reason, p.name AS petName, p.type AS petType FROM Appointments a INNER JOIN Pets p ON a.petId = p.id WHERE p.username = :username ORDER BY a.date ASC, a.time ASC");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    $appointments = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $appointments[] = $row;
    }

    echo json_encode(["success" => true, "appointments" => $appointments]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
