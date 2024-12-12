<?php
function petConnect() {
    $db = new PDO('../database/sqlite:petcare.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // pet table
    $db->exec("CREATE TABLE (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT,
        name TEXT,
        age INTEGER,
        history TEXT
    )");
    return $db;
}
?>
