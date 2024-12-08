<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "petcareDB"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tables in the database
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $table = $row[0];
        echo "<h2>Table: $table</h2>";
        
        // Fetch data from the current table
        $dataSql = "SELECT * FROM $table";
        $dataResult = $conn->query($dataSql);
        
        if ($dataResult->num_rows > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>";

            // Print table headers
            $columns = $dataResult->fetch_fields();
            foreach ($columns as $column) {
                echo "<th>" . $column->name . "</th>";
            }
            echo "</tr>";

            // Print table rows
            while ($dataRow = $dataResult->fetch_assoc()) {
                echo "<tr>";
                foreach ($dataRow as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data found in this table.";
        }
    }
} else {
    echo "No tables found in the database.";
}

$conn->close();
?>
