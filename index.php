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
        ORDER BY product_id
        LIMIT 4";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$featuredProducts = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Online Store</title>

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

    <!-- Hero Section -->
    <section class="hero">

        <div class="hero-content">

            <h1>Welcome to Our Online Store</h1>

            <p>
                Find quality technology products at great prices.
                Shop computers, accessories, gaming products, and more.
            </p>

            <a href="products.php" class="btn">
                Shop Now
            </a>

        </div>

    </section>


    <!-- Featured Products -->
    <section class="products-section">

        <div class="section-header">

            <h2>Featured Products</h2>

            <p>
                Check out some of our available products.
            </p>

        </div>


        <div class="product-grid">

            <?php if (!empty($featuredProducts)): ?>

                <?php foreach ($featuredProducts as $product): ?>

                    <div class="product-card">

                        <?php if (!empty($product['image'])): ?>

                            <img
                                class="product-image"
                                src="images/<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            >

                        <?php endif; ?>


                        <div class="product-info">

                            <h3>
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </h3>

                            <p>
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>

                            <div class="product-price">
                                $<?php echo number_format($product['price'], 2); ?>
                            </div>


                            <?php if ((int) $product['quantity_available'] > 0): ?>

                                <p class="stock-status">
                                    In Stock
                                </p>

                            <?php else: ?>

                                <p class="stock-status">
                                    Out of Stock
                                </p>

                            <?php endif; ?>


                            <a
                                href="product.php?id=<?php echo (int) $product['product_id']; ?>"
                                class="btn"
                            >
                                View Product
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-state">

                    <h3>No Products Available</h3>

                    <p>
                        No products are currently available.
                        Please check back later.
                    </p>

                </div>

            <?php endif; ?>

        </div>


        <div class="section-action">

            <a href="products.php" class="btn">
                View All Products
            </a>

        </div>

    </section>


    <!-- Store Features -->
    <section class="features-section">

        <div class="section-header">

            <h2>Why Shop With Us?</h2>

        </div>


        <div class="features-grid">

            <div class="feature-card">

                <h3>Easy Shopping</h3>

                <p>
                    Browse our products and easily add items to your
                    shopping cart.
                </p>

            </div>


            <div class="feature-card">

                <h3>Inventory Tracking</h3>

                <p>
                    Product availability is tracked using our
                    online store inventory system.
                </p>

            </div>


            <div class="feature-card">

                <h3>Secure Checkout</h3>

                <p>
                    Complete your order through our secure checkout
                    process.
                </p>

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