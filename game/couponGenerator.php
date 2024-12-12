<?php
require_once '../database/dbConnect.php';

try {
    // Connect to the SQLite database
    $db = new SQLite3('../database/my_database.sqlite');

    // Create the table if it doesn't exist
    $createTableSQL = "CREATE TABLE IF NOT EXISTS coupons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $db->exec($createTableSQL);

    // Get the JSON data from the request
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['coupon'])) {
        $coupon = $db->escapeString($data['coupon']);

        // Insert the coupon into the database
        $sql = "INSERT INTO coupons (code) VALUES ('$coupon')";

        if ($db->exec($sql)) {
            echo json_encode(["success" => true, "message" => "Coupon saved successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error: Could not save the coupon."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid request data."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
