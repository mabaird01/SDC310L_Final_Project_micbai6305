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
    header("Location: ../cart.php");
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


/*
 * Get requested action.
 */
$action = $_POST['action'] ?? '';


if (!$product_id) {
    header("Location: ../cart.php");
    exit;
}


/*
 * Make sure the product is currently in the cart.
 */
if (!isset($_SESSION['cart'][$product_id])) {
    header("Location: ../cart.php");
    exit;
}


/*
 * Increase quantity.
 */
if ($action === 'increase') {

    $sql = "SELECT quantity_available
            FROM products
            WHERE product_id = :product_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':product_id' => $product_id
    ]);

    $product = $stmt->fetch();


    if ($product) {

        $current_quantity =
            $_SESSION['cart'][$product_id];

        $available_quantity =
            (int) $product['quantity_available'];


        /*
         * Do not allow the cart quantity
         * to exceed inventory.
         */
        if ($current_quantity < $available_quantity) {

            $_SESSION['cart'][$product_id] =
                $current_quantity + 1;

        }
    }
}


/*
 * Decrease quantity.
 */
if ($action === 'decrease') {

    $_SESSION['cart'][$product_id]--;


    /*
     * Remove the product completely
     * if its quantity reaches zero.
     */
    if ($_SESSION['cart'][$product_id] <= 0) {

        unset($_SESSION['cart'][$product_id]);

    }
}


/*
 * Return to the cart.
 */
header("Location: ../cart.php");
exit;