<?php

require_once "php/database.php";

$sql = "SELECT
            product_id,
            product_name,
            description,
            price,
            quantity_available,
            image
        FROM products
        ORDER BY product_name";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$products = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - Online Store</title>

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

    <section class="products-section">

        <div class="section-header">

            <h1>Our Products</h1>

            <p>
                Browse our available products and add items to your cart.
            </p>

        </div>


        <div class="product-grid">

            <?php if (!empty($products)): ?>

                <?php foreach ($products as $product): ?>

                    <div class="product-card">

                        <?php if (!empty($product['image'])): ?>

                            <img
                                class="product-image"
                                src="images/<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            >

                        <?php endif; ?>


                        <div class="product-info">

                            <h2>
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </h2>


                            <p>
                                <?php
                                echo htmlspecialchars(
                                    $product['description']
                                );
                                ?>
                            </p>


                            <div class="product-price">

                                $<?php echo number_format(
                                    $product['price'],
                                    2
                                ); ?>

                            </div>


                            <?php if ($product['quantity_available'] > 0): ?>

                                <p class="stock-status">
                                    In Stock:
                                    <?php
                                    echo (int) $product['quantity_available'];
                                    ?>
                                </p>

                            <?php else: ?>

                                <p class="stock-status">
                                    Out of Stock
                                </p>

                            <?php endif; ?>


                            <div class="product-actions">

                                <a
                                    href="product.php?id=<?php echo $product['product_id']; ?>"
                                    class="btn"
                                >
                                    View Details
                                </a>


                                <?php if ($product['quantity_available'] > 0): ?>

                                    <form
                                        action="api/add_to_cart.php"
                                        method="POST"
                                    >

                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="<?php echo $product['product_id']; ?>"
                                        >

                                        <button
                                            type="submit"
                                            name="add_to_cart"
                                            class="add-to-cart"
                                        >
                                            Add to Cart
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-state">

                    <h2>No Products Available</h2>

                    <p>
                        There are currently no products available.
                    </p>

                </div>

            <?php endif; ?>

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