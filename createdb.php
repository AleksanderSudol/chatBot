<?php

try {
    // Connect to SQLite database or create if xxx.sqlite dosen't exist
    $db = new PDO('sqlite:kemiChatt.sqlite');
    
    // Error mode so can catch if error occurs
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Create table if db dont exist
    $db->exec("CREATE TABLE IF NOT EXISTS users (
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS messages (
        ID INTEGER PRIMARY KEY AUTOINCREMENT,
        userID INTEGER NOT NULL,
        messages TEXT NOT NULL,
        timeStamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (userID) REFERENCES users(ID)
    )");
    
    // Insert BOT as user
    $db->exec("INSERT OR IGNORE INTO users (ID, name) VALUES (0, 'Bot')");
} catch (PDOException $e) {
    // Catch error and display it 
    echo "Connection failed: " . $e->getMessage();
}