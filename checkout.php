<?php

session_start();

require_once "php/database.php";
require_once "php/checkout_functions.php";


/*
 * Make sure the cart exists.
 */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/*
 * Redirect to products if the cart is empty.
 */
if (empty($_SESSION['cart'])) {

    header("Location: products.php");
    exit;

}


/*
 * Retrieve products in the cart.
 */
$productIds = array_keys($_SESSION['cart']);

$placeholders = implode(
    ',',
    array_fill(0, count($productIds), '?')
);


$sql = "SELECT
            product_id,
            product_name,
            price,
            quantity_available,
            image
        FROM products
        WHERE product_id IN ($placeholders)
        ORDER BY product_name";


$stmt = $pdo->prepare($sql);
$stmt->execute($productIds);

$products = $stmt->fetchAll();


/*
 * Calculate current cart total.
 */
$cartTotal = 0.00;

foreach ($products as $product) {

    $productId = $product['product_id'];

    $quantity = $_SESSION['cart'][$productId] ?? 0;

    $cartTotal +=
        $product['price'] * $quantity;

}


?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout - Online Store</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <nav class="navbar">

        <div class="nav-container">

            <a href="index.php" class="logo">
                Online Store
            </a>

            <div class="nav-links">

                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php">Cart</a>
                <a href="checkout.php">Checkout</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

            </div>

        </div>

    </nav>

</header>


<main>

    <section class="checkout-section">

        <div class="section-header">

            <h1>Checkout</h1>

            <p>
                Enter your information to complete your order.
            </p>

        </div>


        <div class="checkout-container">


            <!-- Customer Information -->

            <div class="checkout-form-container">

                <h2>Customer Information</h2>


                <form
                    action="api/checkout.php"
                    method="POST"
                >

                    <div class="form-group">

                        <label for="first_name">
                            First Name
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            maxlength="50"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="last_name">
                            Last Name
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            maxlength="50"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            maxlength="150"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        name="place_order"
                        class="btn"
                    >
                        Place Order
                    </button>

                </form>

            </div>


            <!-- Order Summary -->

            <div class="checkout-summary">

                <h2>Order Summary</h2>


                <?php foreach ($products as $product): ?>

                    <?php
                    $productId = $product['product_id'];
                    $quantity = $_SESSION['cart'][$productId] ?? 0;

                    $subtotal =
                        $product['price'] * $quantity;
                    ?>


                    <div class="checkout-item">

                        <div>

                            <h3>
                                <?php
                                echo htmlspecialchars(
                                    $product['product_name']
                                );
                                ?>
                            </h3>

                            <p>
                                Quantity:
                                <?php echo $quantity; ?>
                            </p>

                        </div>


                        <div>

                            <strong>
                                $<?php echo number_format(
                                    $subtotal,
                                    2
                                ); ?>
                            </strong>

                        </div>

                    </div>

                <?php endforeach; ?>


                <div class="checkout-total">

                    <h2>
                        Total:
                        $<?php echo number_format(
                            $cartTotal,
                            2
                        ); ?>
                    </h2>

                </div>


                <a
                    href="cart.php"
                    class="btn"
                >
                    Return to Cart
                </a>

            </div>

        </div>

    </section>

</main>


<footer>

    <p>
        &copy; <?php echo date("Y"); ?> Online Store.
        All rights reserved.
    </p>

</footer>


<script src="js/script.js"></script>

</body>
</html>