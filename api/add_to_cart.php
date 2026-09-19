<?php

session_start();

require_once "../php/database.php";


/*
 * Make sure the cart exists.
 */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/*
 * Only accept POST requests.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../products.php");
    exit;
}


/*
 * Validate product ID.
 */
$product_id = filter_input(
    INPUT_POST,
    'product_id',
    FILTER_VALIDATE_INT
);

if (!$product_id) {
    header("Location: ../products.php");
    exit;
}


/*
 * Retrieve the product and current inventory.
 */
$sql = "SELECT
            product_id,
            quantity_available
        FROM products
        WHERE product_id = :product_id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':product_id' => $product_id
]);

$product = $stmt->fetch();


/*
 * Product does not exist.
 */
if (!$product) {
    header("Location: ../products.php");
    exit;
}


/*
 * Product is out of stock.
 */
if ((int) $product['quantity_available'] <= 0) {
    header("Location: ../products.php");
    exit;
}


/*
 * Get current cart quantity.
 */
$current_quantity =
    $_SESSION['cart'][$product_id] ?? 0;


/*
 * Only increase the cart quantity if
 * inventory is available.
 */
if ($current_quantity < $product['quantity_available']) {

    $_SESSION['cart'][$product_id] =
        $current_quantity + 1;

}


/*
 * Return to the cart after adding.
 */
header("Location: ../cart.php");
exit;