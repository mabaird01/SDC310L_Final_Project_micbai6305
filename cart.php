<?php

session_start();

require_once "php/database.php";
require_once "php/cart_functions.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartProducts = [];
$cartTotal = 0.00;


/*
 * Get products currently in the cart.
 */
if (!empty($_SESSION['cart'])) {

    $productIds = array_keys($_SESSION['cart']);

    $placeholders = implode(
        ',',
        array_fill(0, count($productIds), '?')
    );

    $sql = "SELECT
                product_id,
                product_name,
                description,
                price,
                quantity_available,
                image
            FROM products
            WHERE product_id IN ($placeholders)
            ORDER BY product_name";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($productIds);

    $cartProducts = $stmt->fetchAll();


    /*
     * Calculate cart total.
     */
    foreach ($cartProducts as &$product) {

        $productId = $product['product_id'];

        $quantity = $_SESSION['cart'][$productId] ?? 0;

        /*
         * Make sure the cart quantity does not exceed inventory.
         */
        if ($quantity > $product['quantity_available']) {

            $quantity = $product['quantity_available'];

            if ($quantity > 0) {

                $_SESSION['cart'][$productId] = $quantity;

            } else {

                unset($_SESSION['cart'][$productId]);

            }
        }


        $product['cart_quantity'] = $quantity;

        $product['subtotal'] =
            $product['price'] * $quantity;

        $cartTotal += $product['subtotal'];

    }

    unset($product);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart - Online Store</title>

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

    <section class="cart-section">

        <div class="section-header">

            <h1>Shopping Cart</h1>

        </div>


        <?php if (!empty($cartProducts)): ?>

            <div class="cart-container">

                <?php foreach ($cartProducts as $product): ?>

                    <div class="cart-item">


                        <div class="cart-item-image">

                            <?php if (!empty($product['image'])): ?>

                                <img
                                    src="images/<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                >

                            <?php endif; ?>

                        </div>


                        <div class="cart-item-details">

                            <h2>
                                <?php
                                echo htmlspecialchars(
                                    $product['product_name']
                                );
                                ?>
                            </h2>

                            <p>
                                $<?php echo number_format(
                                    $product['price'],
                                    2
                                ); ?>
                                each
                            </p>


                            <p>
                                Subtotal:
                                <strong>
                                    $<?php echo number_format(
                                        $product['subtotal'],
                                        2
                                    ); ?>
                                </strong>
                            </p>

                        </div>


                        <div class="cart-item-actions">


                            <!-- Decrease -->
                            <form
                                action="api/update_cart.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php echo $product['product_id']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="decrease"
                                >

                                <button
                                    type="submit"
                                    class="quantity-button"
                                >
                                    -
                                </button>

                            </form>


                            <span class="cart-quantity">
                                <?php echo $product['cart_quantity']; ?>
                            </span>


                            <!-- Increase -->
                            <form
                                action="api/update_cart.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php echo $product['product_id']; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="increase"
                                >

                                <button
                                    type="submit"
                                    class="quantity-button"
                                >
                                    +
                                </button>

                            </form>


                            <!-- Remove -->
                            <form
                                action="api/remove_from_cart.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php echo $product['product_id']; ?>"
                                >

                                <button
                                    type="submit"
                                    class="remove-button"
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>


                <div class="cart-summary">

                    <h2>
                        Cart Total:
                        $<?php echo number_format($cartTotal, 2); ?>
                    </h2>


                    <div class="cart-buttons">

                        <a
                            href="products.php"
                            class="btn"
                        >
                            Continue Shopping
                        </a>


                        <a
                            href="checkout.php"
                            class="btn"
                        >
                            Proceed to Checkout
                        </a>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <h2>Your Cart Is Empty</h2>

                <p>
                    You have not added any products to your cart yet.
                </p>

                <a
                    href="products.php"
                    class="btn"
                >
                    Start Shopping
                </a>

            </div>

        <?php endif; ?>

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