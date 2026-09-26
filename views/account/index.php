<?php

$pageTitle = "My Account";

require __DIR__ . "/../layouts/header.php";
?>

<main>

    <section class="checkout-section">

        <div class="section-header">

            <h1>
                My Account
            </h1>

            <p>
                View your account information and order history.
            </p>

        </div>


        <!-- =========================
             ACCOUNT INFORMATION
             ========================= -->

        <div class="checkout-summary">

            <h2>
                Account Information
            </h2>

            <div class="checkout-item">

                <div>

                    <strong>
                        Name
                    </strong>

                    <p>
                        <?= htmlspecialchars(
                            $user['first_name']
                        ) ?>

                        <?= htmlspecialchars(
                            $user['last_name']
                        ) ?>
                    </p>

                </div>

            </div>


            <div class="checkout-item">

                <div>

                    <strong>
                        Email
                    </strong>

                    <p>
                        <?= htmlspecialchars(
                            $user['email']
                        ) ?>
                    </p>

                </div>

            </div>

        </div>


        <!-- =========================
             ORDER HISTORY
             ========================= -->

        <div class="checkout-summary">

            <h2>
                Order History
            </h2>


            <?php if (empty($orders)): ?>

                <div class="empty-state">

                    <h3>
                        No orders yet
                    </h3>

                    <p>
                        You have not placed any orders yet.
                    </p>

                    <a
                        href="<?= SITE_URL ?>products.php"
                        class="btn"
                    >
                        Start Shopping
                    </a>

                </div>

            <?php else: ?>

                <?php foreach ($orders as $order): ?>

                    <div class="checkout-item">

                        <div>

                            <h3> 
                                <a 
                                    href="<?= SITE_URL ?>order.php?id=<?= (int) $order['order_id'] ?>" 
                                > 
                                    Order #<?= (int) $order['order_id'] ?> 
                                </a> 
                            </h3>

                            <p>
                                Date:
                                <?= htmlspecialchars(
                                    date(
                                        "F j, Y g:i A",
                                        strtotime(
                                            $order['order_date']
                                        )
                                    )
                                ) ?>
                            </p>

                            <p>
                                Items:
                                <?= count($order['items']) ?>
                            </p>

                        </div>


                        <div class="checkout-item-price">

                            <strong>
                                $<?= number_format(
                                    (float) $order['total'],
                                    2
                                ) ?>
                            </strong>

                        </div>

                    </div>


                    <?php if (!empty($order['items'])): ?>

                        <div class="order-items">

                            <?php foreach ($order['items'] as $item): ?>

                                <p>

                                    <?= htmlspecialchars(
                                        $item['product_name']
                                    ) ?>

                                    ×
                                    <?= (int) $item['quantity'] ?>

                                    —
                                    $<?= number_format(
                                        (float) $item['price']
                                        * (int) $item['quantity'],
                                        2
                                    ) ?>

                                </p>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>


        <!-- =========================
             ACCOUNT ACTIONS
             ========================= -->

        <div class="section-action">

            <a
                href="<?= SITE_URL ?>products.php"
                class="btn"
            >
                Continue Shopping
            </a>

            <a
                href="<?= SITE_URL ?>api/logout.php"
                class="btn"
            >
                Logout
            </a>

        </div>

    </section>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>