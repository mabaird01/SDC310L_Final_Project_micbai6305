<?php

require_once __DIR__ . "/../../php/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? SITE_NAME;

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($pageTitle) ?>
    </title>

    <link
        rel="stylesheet"
        href="<?= SITE_URL ?>css/style.css"
    >

</head>

<body>

<header>

    <nav class="navbar">

        <div class="nav-container">

            <a
                href="<?= SITE_URL ?>index.php"
                class="logo"
            >
                <?= htmlspecialchars(SITE_NAME) ?>
            </a>

            <ul class="nav-links">

                <li>
                    <a href="<?= SITE_URL ?>index.php">
                        Home
                    </a>
                </li>

                <li>
                    <a href="<?= SITE_URL ?>products.php">
                        Products
                    </a>
                </li>

                <li>
                    <a href="<?= SITE_URL ?>cart.php">
                        Cart
                    </a>
                </li>

                <li>
                    <a href="<?= SITE_URL ?>checkout.php">
                        Checkout
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>

                    <li>
                        <span class="nav-user">
                            Welcome,
                            <?= htmlspecialchars(
                            $_SESSION['user_first_name']
                            ) ?>
                        </span>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>account.php">
                            My Account
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>api/logout.php">
                            Logout
                        </a>
                    </li>

                <?php else: ?>

                    <li>
                        <a href="<?= SITE_URL ?>login.php">
                            Login
                        </a>
                    </li>

                    <li>
                        <a href="<?= SITE_URL ?>register.php">
                            Register
                        </a>
                    </li>

                <?php endif; ?>


            </ul>

        </div>

    </nav>

</header>