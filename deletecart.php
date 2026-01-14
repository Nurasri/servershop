<?php
header('Content-Type: application/json');

// Terima JSON
$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'] ?? 0;
$user_id = $data['user_id'] ?? 0;

if (!$cart_id || !$user_id) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Cart ID dan User ID harus diisi'
    ]);
    exit;
}

// Koneksi database
$conn = mysqli_connect('localhost', 'root', '', 'db_ecommerce');

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Hapus item dari cart
$deleteQuery = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";

if (mysqli_query($conn, $deleteQuery)) {
    echo json_encode([
        'success' => true,
        'message' => 'Item dihapus dari keranjang'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal hapus item: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
