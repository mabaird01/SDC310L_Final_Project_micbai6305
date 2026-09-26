<?php

require __DIR__ . "/../layouts/header.php";
?>

<main class="product-page">

    <section class="product-detail-section">

        <?php if (isset($message)): ?>

            <div class="product-not-found">

                <h1>Product Not Found</h1>

                <p><?= htmlspecialchars($message) ?></p>

                <a href="products.php" class="btn btn-primary">
                    Back to Products
                </a>

            </div>

        <?php else: ?>

            <div class="product-detail">

                <div class="product-detail-image">

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


                <div class="product-detail-info">

                    <h1>
                        <?= htmlspecialchars($product['product_name']) ?>
                    </h1>

                    <p class="product-detail-price">
                        $<?= number_format((float) $product['price'], 2) ?>
                    </p>

                    <p class="product-detail-description">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>


                    <?php if ((int) $product['quantity_available'] > 0): ?>

                        <p class="product-stock in-stock">
                            In Stock:
                            <?= (int) $product['quantity_available'] ?>
                        </p>

                        <form
                            action="api/add_to_cart.php"
                            method="POST"
                            class="add-to-cart-form"
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

                        <p class="product-stock out-of-stock">
                            Out of Stock
                        </p>

                        <button
                            type="button"
                            class="btn btn-disabled"
                            disabled
                        >
                            Out of Stock
                        </button>

                    <?php endif; ?>


                    <div class="product-detail-actions">

                        <a
                            href="products.php"
                            class="btn btn-secondary"
                        >
                            Back to Products
                        </a>

                        <a
                            href="cart.php"
                            class="btn btn-secondary"
                        >
                            View Cart
                        </a>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php
require __DIR__ . "/../layouts/footer.php";
?>