<?php

$pageTitle = "Checkout";

require __DIR__ . "/../layouts/header.php";

$isLoggedIn = isset($_SESSION['user_id']);
?>

<main>

    <section class="checkout-section">

        <div class="section-header">

            <h1>
                Checkout
            </h1>

            <p>
                Review your order and complete your purchase.
            </p>

        </div>


        <?php if (!empty($checkoutError)): ?>

            <div class="empty-state">

                <h3>
                    Checkout Error
                </h3>

                <p>
                    <?= htmlspecialchars($checkoutError) ?>
                </p>

            </div>

        <?php endif; ?>


        <div class="checkout-container">

            <!-- =========================
                 CUSTOMER INFORMATION
                 ========================= -->

            <div class="checkout-form-container">

                <h2>
                    Customer Information
                </h2>


                <?php if ($isLoggedIn): ?>

                    <div class="form-group">

                        <label>
                            First Name
                        </label>

                        <input
                            type="text"
                            value="<?= htmlspecialchars(
                                $_SESSION['user_first_name'] ?? ''
                            ) ?>"
                            readonly
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Last Name
                        </label>

                        <input
                            type="text"
                            value="<?= htmlspecialchars(
                                $_SESSION['user_last_name'] ?? ''
                            ) ?>"
                            readonly
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Email Address
                        </label>

                        <input
                            type="email"
                            value="<?= htmlspecialchars(
                                $_SESSION['user_email'] ?? ''
                            ) ?>"
                            readonly
                        >

                    </div>


                    <p>
                        You are checking out as
                        <strong>
                            <?= htmlspecialchars(
                                $_SESSION['user_first_name'] ?? ''
                            ) ?>
                        </strong>.
                    </p>


                    <p>
                        Not you?
                        <a href="<?= SITE_URL ?>api/logout.php">
                            Logout
                        </a>
                        and use a different account.
                    </p>


                    <form
                        action="<?= SITE_URL ?>api/checkout.php"
                        method="POST"
                    >

                        <button
                            type="submit"
                            class="btn"
                        >
                            Place Order
                        </button>

                    </form>

                <?php else: ?>

                    <p>
                        Already have an account?
                        <a href="<?= SITE_URL ?>login.php">
                            Login
                        </a>
                        before checking out.
                    </p>


                    <form
                        action="<?= SITE_URL ?>api/checkout.php"
                        method="POST"
                    >

                        <div class="form-group">

                            <label for="first_name">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                maxlength="50"
                                required
                                autocomplete="given-name"
                            >

                        </div>


                        <div class="form-group">

                            <label for="last_name">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                maxlength="50"
                                required
                                autocomplete="family-name"
                            >

                        </div>


                        <div class="form-group">

                            <label for="email">
                                Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                maxlength="150"
                                required
                                autocomplete="email"
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn"
                        >
                            Place Order
                        </button>

                    </form>

                <?php endif; ?>

            </div>


            <!-- =========================
                 ORDER SUMMARY
                 ========================= -->

            <div class="checkout-summary">

                <h2>
                    Order Summary
                </h2>


                <?php foreach ($cartItems as $item): ?>

                    <div class="checkout-item">

                        <div>

                            <div class="checkout-item-name">

                                <?= htmlspecialchars(
                                    $item['product_name']
                                ) ?>

                            </div>

                            <small>
                                Quantity:
                                <?= (int) $item['quantity'] ?>
                            </small>

                        </div>


                        <div class="checkout-item-price">

                            $<?= number_format(
                                (float) $item['subtotal'],
                                2
                            ) ?>

                        </div>

                    </div>

                <?php endforeach; ?>


                <div class="checkout-total">

                    <span>
                        Total
                    </span>

                    <span>
                        $<?= htmlspecialchars($cartTotal) ?>
                    </span>

                </div>

            </div>

        </div>


        <div class="section-action">

            <a
                href="<?= SITE_URL ?>cart.php"
                class="btn"
            >
                Return to Cart
            </a>

        </div>

    </section>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>