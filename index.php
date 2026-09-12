<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DarkStoreTech | Online Store</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navigation -->
    <header class="site-header">

        <nav class="navbar">

            <div class="logo">
                <a href="index.php">DarkStoreTech</a>
            </div>

            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>

            <div class="cart-button">
                <a href="cart.php">
                    🛒 Cart
                    <span class="cart-count">0</span>
                </a>
            </div>

        </nav>

    </header>


    <!-- Hero Section -->
    <main>

        <section class="hero">

            <div class="hero-content">

                <p class="hero-label">WELCOME TO DARKSTORETECH</p>

                <h1>
                    Technology for your
                    <span>everyday life.</span>
                </h1>

                <p class="hero-description">
                    Discover quality products at competitive prices.
                    Browse our collection and find something you'll love.
                </p>

                <div class="hero-buttons">
                    <a href="products.php" class="btn btn-primary">
                        Shop Now
                    </a>

                    <a href="#featured" class="btn btn-secondary">
                        View Featured
                    </a>
                </div>

            </div>

        </section>


        <!-- Featured Products -->
        <section class="products-section" id="featured">

            <div class="section-header">

                <div>
                    <p class="section-label">OUR COLLECTION</p>
                    <h2>Featured Products</h2>
                </div>

                <a href="products.php" class="view-all">
                    View All →
                </a>

            </div>


            <div class="product-grid">

                <!-- Product 1 -->
                <article class="product-card">

                    <div class="product-image">
                        <span>PRODUCT</span>
                    </div>

                    <div class="product-info">

                        <p class="product-category">
                            Electronics
                        </p>

                        <h3>Wireless Headphones</h3>

                        <p class="product-description">
                            High-quality wireless headphones
                            with noise cancellation.
                        </p>

                        <div class="product-bottom">

                            <span class="price">
                                $79.99
                            </span>

                            <a href="product.php?id=1"
                               class="add-button">
                                View
                            </a>

                        </div>

                    </div>

                </article>


                <!-- Product 2 -->
                <article class="product-card">

                    <div class="product-image">
                        <span>PRODUCT</span>
                    </div>

                    <div class="product-info">

                        <p class="product-category">
                            Accessories
                        </p>

                        <h3>Mechanical Keyboard</h3>

                        <p class="product-description">
                            A responsive mechanical keyboard
                            designed for work and gaming.
                        </p>

                        <div class="product-bottom">

                            <span class="price">
                                $89.99
                            </span>

                            <a href="product.php?id=2"
                               class="add-button">
                                View
                            </a>

                        </div>

                    </div>

                </article>


                <!-- Product 3 -->
                <article class="product-card">

                    <div class="product-image">
                        <span>PRODUCT</span>
                    </div>

                    <div class="product-info">

                        <p class="product-category">
                            Accessories
                        </p>

                        <h3>Wireless Mouse</h3>

                        <p class="product-description">
                            Ergonomic wireless mouse with
                            precision tracking.
                        </p>

                        <div class="product-bottom">

                            <span class="price">
                                $39.99
                            </span>

                            <a href="product.php?id=3"
                               class="add-button">
                                View
                            </a>

                        </div>

                    </div>

                </article>


                <!-- Product 4 -->
                <article class="product-card">

                    <div class="product-image">
                        <span>PRODUCT</span>
                    </div>

                    <div class="product-info">

                        <p class="product-category">
                            Electronics
                        </p>

                        <h3>USB-C Hub</h3>

                        <p class="product-description">
                            Expand your computer's connectivity
                            with multiple USB-C ports.
                        </p>

                        <div class="product-bottom">

                            <span class="price">
                                $49.99
                            </span>

                            <a href="product.php?id=4"
                               class="add-button">
                                View
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        </section>


        <!-- Store Features -->
        <section class="features">

            <div class="feature">

                <div class="feature-icon">
                    ✓
                </div>

                <div>
                    <h3>Quality Products</h3>
                    <p>
                        Products selected with quality in mind.
                    </p>
                </div>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    $
                </div>

                <div>
                    <h3>Competitive Pricing</h3>
                    <p>
                        Great products at reasonable prices.
                    </p>
                </div>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    ↻
                </div>

                <div>
                    <h3>Easy Shopping</h3>
                    <p>
                        Simple shopping and checkout experience.
                    </p>
                </div>

            </div>

        </section>

    </main>


    <!-- Footer -->
    <footer class="site-footer">

        <div class="footer-content">

            <div>
                <h3>DarkStoreTech</h3>
                <p>
                    Your online destination for quality products.
                </p>
            </div>

            <div>
                <h4>Store</h4>
                <a href="products.php">Products</a>
                <a href="cart.php">Shopping Cart</a>
                <a href="checkout.php">Checkout</a>
            </div>

            <div>
                <h4>Account</h4>
                <a href="login.php">Login</a>
                <a href="register.php">Create Account</a>
            </div>

        </div>

        <div class="footer-bottom">
            <p>
                &copy; 2026 DarkStoreTech. All rights reserved.
            </p>
        </div>

    </footer>

</body>
</html>