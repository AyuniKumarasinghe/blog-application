<?php

// Load environment variables from .env
$envFile = __DIR__ . "/../.env";

if (!file_exists($envFile)) {
    die("Error: .env file not found.");
}

$env = parse_ini_file($envFile);

if ($env === false) {
    die("Error: Unable to read .env file.");
}


// Database configuration
$host = $env["DB_HOST"];
$dbname = $env["DB_NAME"];
$username = $env["DB_USER"];
$password = $env["DB_PASS"];


try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die("Database connection failed.");

}