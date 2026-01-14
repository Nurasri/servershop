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

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Email dan password harus diisi'
    ]);
    exit;
}

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_ecommerce';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error koneksi database: ' . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($conn, "utf8");

$query = "SELECT id, nama, email, password FROM users WHERE email = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error prepare statement: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil',
            'user_id' => $user['id'],
            'user_name' => $user['nama'],
            'user_email' => $user['email']
        ]);
    } else {
        http_response_code(200);
        echo json_encode([
            'success' => false,
            'message' => 'Email atau Password salah'
        ]);
    }
} else {
    http_response_code(200);
    echo json_encode([
        'success' => false,
        'message' => 'Email atau Password salah'
    ]);
}

$stmt->close();
$conn->close();
