<?php

$dbname = '../database/petcareDB.sqlite';

// Create connection
$conn = new SQLite3($dbname, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
$conn->exec('PRAGMA foreign_keys = ON;');

// Ensure the 'coupons' table exists
$sql_coupons = "CREATE TABLE IF NOT EXISTS coupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAM
)";
if (!$conn->exec($sql_coupons)) {
    die("Error creating table 'coupons': " . $conn->lastErrorMsg());
}

// Read POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if user is logged in
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to view pets."]);
    exit;
}



if (isset($data['coupon'])) {
    $couponCode = $data['coupon'];

    $user = $_SESSION['username'];

    // Insert coupon and username into database
    $stmt = $conn->prepare("INSERT INTO coupons (username, code) VALUES (:username, :code)");
    $stmt->bindValue(':username', $user, SQLITE3_TEXT);
    $stmt->bindValue(':code', $couponCode, SQLITE3_TEXT);

    if ($stmt->execute()) {
        // Success response
        http_response_code(200);
        echo json_encode(["message" => "Coupon added successfully."]);
    } else {
        // Error response
        http_response_code(500);
        echo json_encode(["message" => "Failed to add coupon: " . $conn->lastErrorMsg()]);
    }
} else {
    // Invalid request response
    http_response_code(400);
    echo json_encode(["message" => "Invalid request. No coupon provided."]);
}

$conn->close();
?>
