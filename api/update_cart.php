<?php

session_start();

require_once __DIR__ . "/../php/database.php";


// --------------------------------------------------
// Validate request method
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../cart.php");
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
    header("Location: ../cart.php");
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

if ($quantity === false || $quantity === null) {
    header("Location: ../cart.php");
    exit;
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

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    header("Location: ../cart.php");
    exit;
}


// --------------------------------------------------
// Get available inventory
// --------------------------------------------------

$availableQuantity =
    (int) $product['quantity_available'];


// --------------------------------------------------
// Quantity of zero removes the item
// --------------------------------------------------

if ($quantity <= 0) {

    unset($_SESSION['cart'][$productId]);

    header("Location: ../cart.php");
    exit;
}


// --------------------------------------------------
// Limit quantity to available inventory
// --------------------------------------------------

if ($quantity > $availableQuantity) {
    $quantity = $availableQuantity;
}


// --------------------------------------------------
// Update cart
// --------------------------------------------------

if ($quantity > 0) {

    $_SESSION['cart'][$productId] = $quantity;

} else {

    unset($_SESSION['cart'][$productId]);
}


// --------------------------------------------------
// Return to cart
// --------------------------------------------------

header("Location: ../cart.php");

exit;