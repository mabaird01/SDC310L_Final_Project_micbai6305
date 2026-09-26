<?php

$pageTitle = "Login";

require __DIR__ . "/../layouts/header.php";
?>

<main class="container">

    <section class="form-section">

        <h1>
            Login
        </h1>


        <?php if (!empty($loginError)): ?>

            <div class="error-message">

                <?= htmlspecialchars($loginError) ?>

            </div>

        <?php endif; ?>


        <form
            action="<?= SITE_URL ?>api/login.php"
            method="POST"
            class="auth-form"
        >

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


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >

            </div>


            <button
                type="submit"
                class="btn"
            >
                Login
            </button>

        </form>


        <p class="form-footer">

            Don't have an account?

            <a href="<?= SITE_URL ?>register.php">
                Create an account
            </a>

        </p>

    </section>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>