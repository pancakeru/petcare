<?php
header('Content-Type: application/json');
include '../database/petConnect.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to book an appointment."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $petId = $data['petId'] ?? null;
    $date = $data['date'] ?? null;
    $time = $data['time'] ?? null;
    $reason = $data['reason'] ?? null;

    if (!$petId || !$date || !$time || !$reason) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO Appointments (petId, date, time, reason) VALUES (:petId, :date, :time, :reason)");
        $stmt->bindValue(':petId', $petId, SQLITE3_INTEGER);
        $stmt->bindValue(':date', $date, SQLITE3_TEXT);
        $stmt->bindValue(':time', $time, SQLITE3_TEXT);
        $stmt->bindValue(':reason', $reason, SQLITE3_TEXT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Appointment booked successfully!"]);
        } else {
            throw new Exception("Failed to save the appointment.");
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
