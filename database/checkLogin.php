<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in
    echo json_encode(["loggedIn" => true]);
} else {
    // User is not logged in
    echo json_encode(["loggedIn" => false]);
}
?>
