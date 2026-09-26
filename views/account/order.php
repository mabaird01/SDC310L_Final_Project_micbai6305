<?php

$pageTitle =
    "Order #" . (int) $order['order_id'];

require __DIR__ . "/../layouts/header.php";
?>

<main>

    <section class="checkout-section">

        <div class="section-header">

            <h1>
                Order #<?= (int) $order['order_id'] ?>
            </h1>

            <p>
                Order placed on
                <?= htmlspecialchars(
                    date(
                        "F j, Y g:i A",
                        strtotime($order['order_date'])
                    )
                ) ?>
            </p>

        </div>


        <!-- =========================
             ORDER ITEMS
             ========================= -->

        <div class="checkout-summary">

            <h2>
                Order Items
            </h2>


            <?php foreach ($orderItems as $item): ?>

                <div class="checkout-item">

                    <div>

                        <?php if (!empty($item['image'])): ?>

                            <img
                                src="<?= SITE_URL ?>images/<?= htmlspecialchars(
                                    $item['image']
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $item['product_name']
                                ) ?>"
                                class="cart-item-image"
                            >

                        <?php endif; ?>

                    </div>


                    <div class="checkout-item-name">

                        <strong>
                            <?= htmlspecialchars(
                                $item['product_name']
                            ) ?>
                        </strong>

                        <p>
                            Quantity:
                            <?= (int) $item['quantity'] ?>
                        </p>

                        <p>
                            Price:
                            $<?= number_format(
                                (float) $item['price'],
                                2
                            ) ?>
                        </p>

                    </div>


                    <div class="checkout-item-price">

                        $<?= number_format(
                            (float) $item['price']
                            * (int) $item['quantity'],
                            2
                        ) ?>

                    </div>

                </div>

            <?php endforeach; ?>


            <!-- =========================
                 ORDER TOTAL
                 ========================= -->

            <div class="checkout-total">

                <span>
                    Order Total
                </span>

                <span>
                    $<?= number_format(
                        (float) $order['total'],
                        2
                    ) ?>
                </span>

            </div>

        </div>


        <!-- =========================
             ACTIONS
             ========================= -->

        <div class="section-action">

            <a
                href="<?= SITE_URL ?>account.php"
                class="btn"
            >
                Back to My Account
            </a>

            <a
                href="<?= SITE_URL ?>products.php"
                class="btn"
            >
                Continue Shopping
            </a>

        </div>

    </section>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>