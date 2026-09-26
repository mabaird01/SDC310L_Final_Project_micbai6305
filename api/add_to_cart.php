<?php

session_start();

require_once __DIR__ . "/../php/database.php";


// --------------------------------------------------
// Validate request method
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../products.php");

    exit;
}


// --------------------------------------------------
// Validate product ID
// --------------------------------------------------

$productId = filter_input(
    INPUT_POST,
    'product_id',
    FILTER_VALIDATE_INT
);

if (!$productId || $productId <= 0) {

    header("Location: ../products.php");

    exit;
}


// --------------------------------------------------
// Validate quantity
// --------------------------------------------------

$quantity = filter_input(
    INPUT_POST,
    'quantity',
    FILTER_VALIDATE_INT
);

if (!$quantity || $quantity <= 0) {

    $quantity = 1;
}


// --------------------------------------------------
// Make sure the product exists
// --------------------------------------------------

$sql = "SELECT
            product_id,
            quantity_available
        FROM products
        WHERE product_id = :product_id
        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':product_id' => $productId
]);

$product = $stmt->fetch();


// --------------------------------------------------
// Product does not exist
// --------------------------------------------------

if (!$product) {

    header("Location: ../products.php");

    exit;
}


// --------------------------------------------------
// Product is out of stock
// --------------------------------------------------

$availableQuantity =
    (int) $product['quantity_available'];

if ($availableQuantity <= 0) {

    header(
        "Location: ../product.php?id="
        . $productId
    );

    exit;
}


// --------------------------------------------------
// Start cart
// --------------------------------------------------

if (!isset($_SESSION['cart'])) {

    $_SESSION['cart'] = [];
}


// --------------------------------------------------
// Add to existing cart quantity
// --------------------------------------------------

$currentQuantity =
    (int) (
        $_SESSION['cart'][$productId]
        ?? 0
    );

$newQuantity =
    $currentQuantity + $quantity;


// --------------------------------------------------
// Do not allow cart quantity to exceed inventory
// --------------------------------------------------

if ($newQuantity > $availableQuantity) {

    $newQuantity = $availableQuantity;
}


$_SESSION['cart'][$productId] = $newQuantity;


// --------------------------------------------------
// Return to cart
// --------------------------------------------------

header("Location: ../cart.php");

exit;