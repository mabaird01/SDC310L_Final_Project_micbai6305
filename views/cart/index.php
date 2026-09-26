<?php

$pageTitle = "Shopping Cart";

require __DIR__ . "/../layouts/header.php";

?>

<main>

    <section class="cart-section">

        <div class="section-header">

            <h1>Shopping Cart</h1>

            <p>
                Review your items before checking out.
            </p>

        </div>

        <?php if (empty($cartItems)): ?>

            <div class="empty-state">

                <h3>Your cart is empty</h3>

                <p>
                    Add some products to your cart to get started.
                </p>

                <a
                    href="<?= SITE_URL ?>products.php"
                    class="btn"
                >
                    Continue Shopping
                </a>

            </div>

        <?php else: ?>

            <div class="cart-items">

                <?php foreach ($cartItems as $item): ?>

                    <div class="cart-item">

                        <div class="cart-item-image-container">

                            <?php if (!empty($item['image'])): ?>

                                <img
                                    src="<?= SITE_URL ?>images/<?= htmlspecialchars($item['image']) ?>"
                                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                                    class="cart-item-image"
                                >

                            <?php endif; ?>

                        </div>

                        <div class="cart-item-details">

                            <h3>
                                <?= htmlspecialchars($item['product_name']) ?>
                            </h3>

                            <?php if (!empty($item['description'])): ?>

                                <p>
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>

                            <?php endif; ?>

                            <p class="cart-item-price">

                                $<?= number_format(
                                    (float) $item['price'],
                                    2
                                ) ?>

                                each

                            </p>

                        </div>

                        <div class="cart-item-quantity">

                            <form
                                action="<?= SITE_URL ?>api/update_cart.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= (int) $item['product_id'] ?>"
                                >

                                <label
                                    for="quantity-<?= (int) $item['product_id'] ?>"
                                >
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    id="quantity-<?= (int) $item['product_id'] ?>"
                                    name="quantity"
                                    value="<?= (int) $item['quantity'] ?>"
                                    min="1"
                                    max="<?= (int) $item['quantity_available'] ?>"
                                    required
                                >

                                <button
                                    type="submit"
                                    class="btn"
                                >
                                    Update
                                </button>

                            </form>

                        </div>

                        <div class="cart-item-subtotal">

                            <strong>
                                $<?= number_format(
                                    (float) $item['subtotal'],
                                    2
                                ) ?>
                            </strong>

                        </div>

                        <div class="cart-item-remove">

                            <form
                                action="<?= SITE_URL ?>api/remove_from_cart.php"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?= (int) $item['product_id'] ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn"
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <div class="cart-summary">

                <h2>Cart Total</h2>

                <div class="cart-total">

                    <span>Total:</span>

                    <strong>
                        $<?= htmlspecialchars($cartTotal) ?>
                    </strong>

                </div>

                <div class="section-action">

                    <a
                        href="<?= SITE_URL ?>products.php"
                        class="btn"
                    >
                        Continue Shopping
                    </a>

                    <a
                        href="<?= SITE_URL ?>checkout.php"
                        class="btn"
                    >
                        Proceed to Checkout
                    </a>

                </div>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php

require __DIR__ . "/../layouts/footer.php";

?>