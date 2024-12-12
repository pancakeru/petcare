<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['loggedIn']) === true) {
    echo json_encode(["loggedIn" => true]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>
