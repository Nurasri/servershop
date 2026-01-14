<?php
$host = getenv("DB_HOST");
$port = getenv("DB_PORT");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$db   = getenv("DB_NAME");

// Debug cepat (boleh dihapus nanti)
// var_dump($host, $user, $db);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

echo "DB CONNECTED SUCCESS";
