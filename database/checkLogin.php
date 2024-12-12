<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['user_logged_in'])) {
    echo json_encode(["loggedIn" => true]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>
