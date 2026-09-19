<?php

session_start();

require_once "../php/database.php";


/*
 * Only accept POST requests.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../checkout.php");
    exit;
}


/*
 * Make sure the cart exists.
 */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/*
 * Do not allow checkout with an empty cart.
 */
if (empty($_SESSION['cart'])) {
    header("Location: ../products.php");
    exit;
}


/*
 * Retrieve customer information.
 */
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');


/*
 * Validate first name.
 */
if ($first_name === '') {

    header("Location: ../checkout.php?error=first_name");
    exit;

}


/*
 * Validate last name.
 */
if ($last_name === '') {

    header("Location: ../checkout.php?error=last_name");
    exit;

}


/*
 * Validate email.
 */
if (
    $email === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {

    header("Location: ../checkout.php?error=email");
    exit;

}


/*
 * Get product IDs from the session cart.
 */
$productIds = array_keys($_SESSION['cart']);

if (empty($productIds)) {
    header("Location: ../products.php");
    exit;
}


try {

    /*
     * Start database transaction.
     */
    $pdo->beginTransaction();


    /*
     * Create placeholders for the product query.
     */
    $placeholders = implode(
        ',',
        array_fill(0, count($productIds), '?')
    );


    /*
     * Retrieve and lock current product inventory.
     *
     * FOR UPDATE prevents another transaction
     * from changing the inventory while checkout
     * is being processed.
     */
    $sql = "SELECT
                product_id,
                product_name,
                price,
                quantity_available
            FROM products
            WHERE product_id IN ($placeholders)
            FOR UPDATE";


    $stmt = $pdo->prepare($sql);

    $stmt->execute($productIds);

    $products = $stmt->fetchAll();


    /*
     * Make sure every product in the session cart
     * still exists in the database.
     */
    if (count($products) !== count($productIds)) {

        throw new Exception(
            "One or more products are no longer available."
        );

    }


    /*
     * Calculate the current order total.
     */
    $orderTotal = 0.00;


    foreach ($products as $product) {

        $productId = (int) $product['product_id'];

        $requestedQuantity =
            (int) ($_SESSION['cart'][$productId] ?? 0);


        /*
         * Make sure the requested quantity is valid.
         */
        if ($requestedQuantity <= 0) {

            throw new Exception(
                "Invalid product quantity."
            );

        }


        /*
         * Check current inventory.
         */
        if (
            $requestedQuantity >
            (int) $product['quantity_available']
        ) {

            throw new Exception(
                "Not enough inventory for " .
                $product['product_name'] . "."
            );

        }


        /*
         * Use the current product price.
         */
        $orderTotal +=
            (float) $product['price'] *
            $requestedQuantity;

    }


    /*
     * Find the customer by email.
     */
    $sql = "SELECT user_id
            FROM users
            WHERE email = :email
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    $user = $stmt->fetch();


    if ($user) {

        /*
         * Existing customer.
         */
        $user_id = (int) $user['user_id'];

    } else {

        /*
         * Create a user account automatically.
         *
         * The password is randomly generated and
         * securely hashed.
         */
        $temporaryPassword = password_hash(
            bin2hex(random_bytes(16)),
            PASSWORD_DEFAULT
        );


        $sql = "INSERT INTO users
                    (
                        first_name,
                        last_name,
                        email,
                        password_hash
                    )
                VALUES
                    (
                        :first_name,
                        :last_name,
                        :email,
                        :password_hash
                    )";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':first_name' =>
                $first_name,

            ':last_name' =>
                $last_name,

            ':email' =>
                $email,

            ':password_hash' =>
                $temporaryPassword
        ]);


        $user_id =
            (int) $pdo->lastInsertId();

    }


    /*
     * Create the order.
     */
    $sql = "INSERT INTO orders
                (
                    user_id,
                    total
                )
            VALUES
                (
                    :user_id,
                    :total
                )";


    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':user_id' =>
            $user_id,

        ':total' =>
            $orderTotal
    ]);


    $order_id =
        (int) $pdo->lastInsertId();


    /*
     * Insert each order item and
     * update product inventory.
     */
    foreach ($products as $product) {

        $productId =
            (int) $product['product_id'];

        $quantity =
            (int) $_SESSION['cart'][$productId];

        $price =
            (float) $product['price'];


        /*
         * Create order item.
         */
        $sql = "INSERT INTO order_items
                    (
                        order_id,
                        product_id,
                        quantity,
                        price
                    )
                VALUES
                    (
                        :order_id,
                        :product_id,
                        :quantity,
                        :price
                    )";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':order_id' =>
                $order_id,

            ':product_id' =>
                $productId,

            ':quantity' =>
                $quantity,

            ':price' =>
                $price
        ]);


        /*
         * Decrease inventory.
         */
        $sql = "UPDATE products
                SET quantity_available =
                    quantity_available - :quantity
                WHERE product_id = :product_id";


        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':quantity' =>
                $quantity,

            ':product_id' =>
                $productId
        ]);

    }


    /*
     * Complete the transaction.
     */
    $pdo->commit();


    /*
     * Clear the shopping cart.
     */
    $_SESSION['cart'] = [];


    /*
     * Store the order ID in the session as well.
     */
    $_SESSION['last_order_id'] = $order_id;


    /*
     * Send the customer to the confirmation page.
     */
    header(
        "Location: ../confirmation.php?order_id=" .
        $order_id
    );

    exit;


} catch (Exception $e) {

    /*
     * Roll back the transaction if anything fails.
     */
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    /*
     * Store a general checkout error.
     *
     * We do not expose database errors directly
     * to the customer.
     */
    $_SESSION['checkout_error'] =
        $e->getMessage();


    header("Location: ../checkout.php?error=checkout");
    exit;
}