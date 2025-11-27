<?php
// includes/config.php
// Start session for all pages that include this file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = '127.0.0.1';
$DB_NAME = 'inventory_db';
$DB_USER = 'root';
$DB_PASS = ''; // change if your MySQL has a password

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // For production hide error details; for dev show
    exit('Database connection failed: ' . $e->getMessage());
}
