<?php

$pageTitle = "Register";

require __DIR__ . "/../layouts/header.php";
?>

<main class="container">

    <section class="form-section">

        <h1>
            Create an Account
        </h1>


        <?php if (!empty($registerError)): ?>

            <div class="error-message">

                <?= htmlspecialchars($registerError) ?>

            </div>

        <?php endif; ?>


        <form
            action="<?= SITE_URL ?>api/register.php"
            method="POST"
            class="auth-form"
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


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                >

            </div>


            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                >

            </div>


            <button
                type="submit"
                class="btn"
            >
                Create Account
            </button>

        </form>


        <p class="form-footer">

            Already have an account?

            <a href="<?= SITE_URL ?>login.php">
                Login
            </a>

        </p>

    </section>

</main>

<?php require __DIR__ . "/../layouts/footer.php"; ?>