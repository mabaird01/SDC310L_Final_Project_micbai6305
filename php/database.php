
<?php

// Database configuration
$host = "localhost";
$dbname = "online_store";
$username = "ecpi_user";
$password = "Password1";

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Tell PDO to throw exceptions when an error occurs
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Return database results as associative arrays
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    // Don't display database details to users
    die("Database connection failed.");
}

