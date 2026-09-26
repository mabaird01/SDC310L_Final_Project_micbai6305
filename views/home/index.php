<?php

$pageTitle = SITE_NAME;

require __DIR__ . "/../layouts/header.php";
?>

<main>

    <!-- =========================
         HERO SECTION
         ========================= -->

    <section class="hero">

        <div class="hero-content">

            <h1>
                Welcome to <?= htmlspecialchars(SITE_NAME) ?>
            </h1>

            <p>
                Shop quality products at great prices.
            </p>

            <a
                href="<?= SITE_URL ?>products.php"
                class="btn"
            >
                Shop Now
            </a>

        </div>

    </section>


    <!-- =========================
         FEATURES SECTION
         ========================= -->

    <section class="features-section">

        <div class="section-header">

            <h2>
                Why Shop With Us?
            </h2>

            <p>
                Everything you need for a simple and convenient
                shopping experience.
            </p>

        </div>

        <div class="features-grid">

            <div class="feature-card">

                <h3>
                    Quality Products
                </h3>

                <p>
                    Browse our selection of quality products
                    for everyday needs.
                </p>

            </div>


            <div class="feature-card">

                <h3>
                    Great Prices
                </h3>

                <p>
                    Find competitive prices on all of our
                    available products.
                </p>

            </div>


            <div class="feature-card">

                <h3>
                    Easy Checkout
                </h3>

                <p>
                    Add products to your cart and complete
                    your order with ease.
                </p>

            </div>

        </div>

    </section>


    <!-- =========================
         FEATURED PRODUCTS
         ========================= -->

    <?php if (!empty($featuredProducts)): ?>

        <section class="products-section">

            <div class="section-header">

                <h2>
                    Featured Products
                </h2>

                <p>
                    Take a look at some of our available products.
                </p>

            </div>

            <div class="product-grid">

                <?php foreach ($featuredProducts as $product): ?>

                    <article class="product-card">

                        <?php if (!empty($product['image'])): ?>

                            <img
                                src="<?= SITE_URL ?>images/<?= htmlspecialchars(
                                    $product['image']
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $product['product_name']
                                ) ?>"
                                class="product-image"
                            >

                        <?php endif; ?>


                        <div class="product-info">

                            <h3>
                                <?= htmlspecialchars(
                                    $product['product_name']
                                ) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars(
                                    $product['description'] ?? ''
                                ) ?>
                            </p>

                            <div class="product-price">

                                $<?= number_format(
                                    (float) $product['price'],
                                    2
                                ) ?>

                            </div>

                            <a
                                href="<?= SITE_URL ?>product.php?id=<?= (int) $product['product_id'] ?>"
                                class="btn"
                            >
                                View Product
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            <div class="section-action">

                <a
                    href="<?= SITE_URL ?>products.php"
                    class="btn"
                >
                    View All Products
                </a>

            </div>

        </section>

    <?php endif; ?>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>