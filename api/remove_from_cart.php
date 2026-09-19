<?php

session_start();


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


if (!$product_id) {
    header("Location: ../cart.php");
    exit;
}


/*
 * Remove the product from the cart.
 */
unset($_SESSION['cart'][$product_id]);


/*
 * Return to the cart.
 */
header("Location: ../cart.php");
exit;