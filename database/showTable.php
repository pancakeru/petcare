<?php
// Connect to the SQLite database
$dbname = 'petcareDB.sqlite';
$conn = new SQLite3($dbname);

// Query to select all table names
$sql = "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'";
$result = $conn->query($sql);

if ($result) {
    echo "<h1>Tables and their contents:</h1>";
    
    // Loop through each table in the database
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $table = $row['name'];
        echo "<h2>Table: $table</h2>";

        // Fetch the content of the current table
        $dataSql = "SELECT * FROM $table";
        $dataResult = $conn->query($dataSql);

        if ($dataResult) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            
            // Print the table headers
            echo "<tr>";
            $columns = $conn->query("PRAGMA table_info($table)");
            $headers = [];
            while ($column = $columns->fetchArray(SQLITE3_ASSOC)) {
                $header = $column['name'];
                $headers[] = $header;
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr>";

            // Print the table rows
            while ($dataRow = $dataResult->fetchArray(SQLITE3_ASSOC)) {
                echo "<tr>";
                foreach ($headers as $header) {
                    echo "<td>" . htmlspecialchars($dataRow[$header]) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data found in this table.";
        }
    }

    // Show the contents of the sqlite_sequence table
    echo "<h2>Table: sqlite_sequence</h2>";
    $sequenceSql = "SELECT * FROM sqlite_sequence";
    $sequenceResult = $conn->query($sequenceSql);

    if ($sequenceResult) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        
        // Print the table headers
        echo "<tr><th>name</th><th>seq</th></tr>";

        // Print the table rows
        while ($sequenceRow = $sequenceResult->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr><td>" . htmlspecialchars($sequenceRow['name']) . "</td><td>" . htmlspecialchars($sequenceRow['seq']) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No data found in the sqlite_sequence table.";
    }
} else {
    echo "No tables found in the database.";
}

// Close the connection
$conn->close();
?>
