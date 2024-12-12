<?php
session_start();

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    echo json_encode(["loggedIn" => true, "username" => $_SESSION['username']]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>
