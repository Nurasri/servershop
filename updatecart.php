<?php
header('Content-Type: application/json');

// Terima JSON
$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'] ?? 0;
$user_id = $data['user_id'] ?? 0;
$quantity = $data['quantity'] ?? 1;

if (!$cart_id || !$user_id || $quantity < 1) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak valid'
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

// Get harga satuan dulu
$priceQuery = "SELECT product_price FROM cart WHERE id = $cart_id AND user_id = $user_id";
$priceResult = mysqli_query($conn, $priceQuery);

if (mysqli_num_rows($priceResult) == 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Item tidak ditemukan'
    ]);
    exit;
}

$row = mysqli_fetch_assoc($priceResult);
$product_price = $row['product_price'];
$subtotal = $product_price * $quantity;

// Update quantity dan subtotal
$updateQuery = "UPDATE cart SET quantity = $quantity, subtotal = $subtotal WHERE id = $cart_id AND user_id = $user_id";

if (mysqli_query($conn, $updateQuery)) {
    echo json_encode([
        'success' => true,
        'message' => 'Keranjang diperbarui',
        'subtotal' => $subtotal
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal update: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
