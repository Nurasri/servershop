<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
include_once 'dbconnect.php';

$stat = $conn->prepare("SELECT id,name,price,promo,description,images,stock,vendors,category FROM product_items WHERE category = 'electronic'");
$stat->execute();

$stat->bind_result($id, $name, $price, $promo, $description, $images, $stock, $vendors, $category);
$arrayproduct = array();

while ($stat->fetch()) {
    $data = array();
    $data['id'] = $id;
    $data['name'] = $name;
    $data['price'] = $price;
    $data['promo'] = $promo;
    $data['description'] = $description;
    $data['images'] = $images;
    $data['stock'] = $stock;
    $data['vendors'] = $vendors;
    $data['category'] = $category;

    array_push($arrayproduct, $data);
}

// header('Content-Type: application.json');
echo json_encode($arrayproduct);
