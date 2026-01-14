<?php
header('Content-Type: application/json');

// Terima JSON dari Flutter
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
$user_id = $data['user_id'] ?? 0;
$product_id = $data['product_id'] ?? 0;
$product_name = $data['product_name'] ?? '';
$product_price = $data['product_price'] ?? 0;
$product_image = $data['product_image'] ?? '';
$quantity = $data['quantity'] ?? 1;

if (!$user_id || !$product_id) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'User ID dan Product ID harus diisi'
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

// Set charset
mysqli_set_charset($conn, "utf8");

// Cek apakah produk sudah ada di cart user ini
$checkQuery = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // Produk sudah ada, update quantity
    $row = mysqli_fetch_assoc($checkResult);
    $newQuantity = $row['quantity'] + $quantity;
    $subtotal = $product_price * $newQuantity;

    $updateQuery = "UPDATE cart SET quantity = $newQuantity, subtotal = $subtotal WHERE id = {$row['id']}";

    if (mysqli_query($conn, $updateQuery)) {
        echo json_encode([
            'success' => true,
            'message' => 'Keranjang diperbarui'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal update keranjang'
        ]);
    }
} else {
    // Produk baru, insert ke cart
    $subtotal = $product_price * $quantity;

    $insertQuery = "INSERT INTO cart (user_id, product_id, product_name, product_price, product_image, quantity, subtotal)
                    VALUES ($user_id, $product_id, '$product_name', $product_price, '$product_image', $quantity, $subtotal)";

    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal tambah ke keranjang: ' . mysqli_error($conn)
        ]);
    }
}

mysqli_close($conn);
