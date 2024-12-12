<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['loggedIn'])) {
    echo json_encode(["loggedIn" => true]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>
