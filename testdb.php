<?php
$host = getenv("DB_HOST");
$port = (int) getenv("DB_PORT"); // 👈 CAST KE INT
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$db   = getenv("DB_NAME");

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("DB FAILED: " . $conn->connect_error);
}

echo "DB CONNECTED SUCCESS";
