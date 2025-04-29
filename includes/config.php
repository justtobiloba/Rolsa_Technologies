<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'rolsa');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>