<?php

$pageTitle = "Order Confirmation";

require __DIR__ . "/../layouts/header.php";
?>

<main>

    <section class="checkout-section">

        <div class="section-header">

            <h1>
                Order Confirmed
            </h1>

            <p>
                Thank you for your purchase.
            </p>

        </div>


        <!-- =========================
             CONFIRMATION MESSAGE
             ========================= -->

        <div class="empty-state">

            <h3>
                Your order has been placed successfully!
            </h3>

            <p>
                Order Number:
                <strong>
                    #<?= (int) $order['order_id'] ?>
                </strong>
            </p>

            <p>
                Order Date:
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
                Order Summary
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

                        <br>

                        <small>
                            Quantity:
                            <?= (int) $item['quantity'] ?>
                        </small>

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
                href="<?= SITE_URL ?>products.php"
                class="btn"
            >
                Continue Shopping
            </a>

        </div>

    </section>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>