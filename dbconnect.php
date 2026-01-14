<?php
$host = getenv("DB_HOST");
$port = (int)getenv("DB_PORT");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$db   = getenv("DB_NAME");

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}

$conn->set_charset("utf8");
