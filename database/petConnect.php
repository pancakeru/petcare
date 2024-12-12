<?php
// Specify the path to the SQLite database
$dbname = '../database/petcareDB.sqlite';

// Create a connection to the SQLite database
try {
    $conn = new SQLite3($dbname);
    $conn->enableExceptions(true); // Enable exceptions for detailed error handling
} catch (Exception $e) {
    // Handle connection errors
    die(json_encode([
        'success' => false,
        'message' => 'Failed to connect to the database: ' . $e->getMessage()
    ]));
}

// Ensure the Pets table exists; create it if it does not
try {
    $sql_pets = "CREATE TABLE IF NOT EXISTS Pets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        type TEXT NOT NULL,
        name TEXT NOT NULL,
        age INTEGER NOT NULL,
        history TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (username) REFERENCES users(username)
    )";

    // Execute the query to create the table
    if (!$conn->exec($sql_pets)) {
        die(json_encode([
            'success' => false,
            'message' => "Error creating table 'Pets': " . $conn->lastErrorMsg()
        ]));
    }
} catch (Exception $e) {
    // Handle errors during table creation
    die(json_encode([
        'success' => false,
        'message' => 'Error setting up database: ' . $e->getMessage()
    ]));
}
?>
