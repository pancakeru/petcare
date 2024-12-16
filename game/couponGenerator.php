<?php

$dbname = '../database/petcareDB.sqlite';

// Create connection
$conn = new SQLite3($dbname);

// Ensure the 'coupons' table exists
$sql_coupons = "CREATE TABLE IF NOT EXISTS coupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->exec($sql_coupons)) {
    die("Error creating table 'coupons': " . $conn->lastErrorMsg());
}

// Read POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['coupon'])) {
    $couponCode = $data['coupon'];

    // Insert coupon into database
    $stmt = $conn->prepare("INSERT INTO coupons (code) VALUES (:code)");
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
