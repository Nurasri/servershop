<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan. Gunakan POST request.'
    ]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$nama = $data['nama'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$telepon = $data['telepon'] ?? '';

if (empty($nama) || empty($email) || empty($password) || empty($telepon)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Semua field harus diisi'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Format email tidak valid'
    ]);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Password minimal 6 karakter'
    ]);
    exit;
}

// Gunakan konfigurasi dari dbconnect.php
include_once 'dbconnect.php';

$checkQuery = "SELECT id FROM users WHERE email = ?";
$checkStmt = $conn->prepare($checkQuery);

if (!$checkStmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error prepare statement: ' . $conn->error
    ]);
    exit;
}

$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    http_response_code(200);
    echo json_encode([
        'success' => false,
        'message' => 'Email sudah terdaftar. Silakan gunakan email lain.'
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}

$checkStmt->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$insertQuery = "INSERT INTO users (nama, email, password, telepon, created_at) VALUES (?, ?, ?, ?, NOW())";
$insertStmt = $conn->prepare($insertQuery);

if (!$insertStmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error prepare statement: ' . $conn->error
    ]);
    exit;
}

$insertStmt->bind_param("ssss", $nama, $email, $hashedPassword, $telepon);

if ($insertStmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Pendaftaran berhasil! Silakan login dengan email Anda.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $insertStmt->error
    ]);
}

$insertStmt->close();
$conn->close();
