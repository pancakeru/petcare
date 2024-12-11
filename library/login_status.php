<?php
session_start();
// Get the path-to-root from JavaScript (if passed as a query parameter)
$path_to_root = isset($_GET['path_to_root']) ? $_GET['path_to_root'] : '';
$path = $path_to_root . 'login/';

if (!empty($_SESSION['user_logged_in'])) {
    echo '<a href="' . $path . 'logout.php" class="login"><span class="nav-item">Log out</span></a>';
} else {
    echo '<a href="' . $path . 'login.php" class="login"><span class="nav-item">Log in</span></a>';
}
?>