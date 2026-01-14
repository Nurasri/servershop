<?php
header('Content-Type: application/json');

// Terima GET parameter
$user_id = $_GET['user_id'] ?? 0;

if (!$user_id) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'User ID harus diisi'
    ]);
    exit;
}

// Gunakan konfigurasi dari dbconnect.php
include_once 'dbconnect.php';

// Query ambil semua item cart user
$query = "SELECT id, product_id, product_name, product_price, product_image, quantity, subtotal FROM cart WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Query error: ' . mysqli_error($conn)
    ]);
    exit;
}

$cartItems = [];
$totalPrice = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = $row;
    $totalPrice += (int)$row['subtotal'];
}

echo json_encode([
    'success' => true,
    'data' => $cartItems,
    'total_price' => $totalPrice,
    'count' => count($cartItems)
]);

mysqli_close($conn);
