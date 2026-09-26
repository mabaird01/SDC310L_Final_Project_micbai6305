<?php

require __DIR__ . "/../layouts/header.php";
?>

<main class="products-page">

    <section class="products-section">

        <div class="section-header">
            <h1>Our Products</h1>
            <p>Browse our selection of technology products.</p>
        </div>

        <div class="products-grid">

            <?php if (empty($products)): ?>

                <div class="empty-state">
                    <h2>No Products Available</h2>
                    <p>There are currently no products available.</p>
                </div>

            <?php else: ?>

                <?php foreach ($products as $product): ?>

                    <article class="product-card">

                        <div class="product-image">
                            <?php if (!empty($product['image'])): ?>

                                <img
                                    src="images/<?= htmlspecialchars($product['image']) ?>"
                                    alt="<?= htmlspecialchars($product['product_name']) ?>"
                                >

                            <?php else: ?>

                                <div class="no-image">
                                    No Image
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="product-info">

                            <h2>
                                <?= htmlspecialchars($product['product_name']) ?>
                            </h2>

                            <p class="product-description">
                                <?= htmlspecialchars($product['description']) ?>
                            </p>

                            <p class="product-price">
                                $<?= number_format((float) $product['price'], 2) ?>
                            </p>

                            <?php if ((int) $product['quantity_available'] > 0): ?>

                                <p class="product-stock in-stock">
                                    In Stock:
                                    <?= (int) $product['quantity_available'] ?>
                                </p>

                            <?php else: ?>

                                <p class="product-stock out-of-stock">
                                    Out of Stock
                                </p>

                            <?php endif; ?>

                            <div class="product-actions">

                                <a
                                    href="product.php?id=<?= (int) $product['product_id'] ?>"
                                    class="btn btn-secondary"
                                >
                                    View Details
                                </a>

                                <?php if ((int) $product['quantity_available'] > 0): ?>

                                    <form
                                        action="api/add_to_cart.php"
                                        method="POST"
                                    >
                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="<?= (int) $product['product_id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            Add to Cart
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <button
                                        type="button"
                                        class="btn btn-disabled"
                                        disabled
                                    >
                                        Out of Stock
                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php
require __DIR__ . "/../layouts/footer.php";
?>