<?php
require "createdb.php"; // Connects to $db

try {
    // Drop tables if they exist
    $db->exec("DROP TABLE IF EXISTS messages");
    $db->exec("DROP TABLE IF EXISTS users");
    
    echo "Database cleaned successfully! Tables have been completely removed.<br>";
    echo "Refresh your chat page to let 'createdb.php' recreate them fresh.";

} catch (PDOException $e) {
    echo "Error cleaning database: " . $e->getMessage();
}