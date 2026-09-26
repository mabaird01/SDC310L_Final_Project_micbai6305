<?php

require_once __DIR__ . "/../php/database.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/OrderItem.php";

class CheckoutController
{
    private PDO $pdo;
    private User $userModel;
    private Order $orderModel;
    private OrderItem $orderItemModel;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;

        $this->userModel = new User($pdo);
        $this->orderModel = new Order($pdo);
        $this->orderItemModel = new OrderItem($pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Display the checkout page.
     */
    public function index(): void
    {
        $cartItems = $this->getCartItems();

        if (empty($cartItems)) {
            header("Location: cart.php");
            exit;
        }

        $cartTotal = 0.00;

        foreach ($cartItems as $item) {
            $cartTotal += $item['subtotal'];
        }

        $cartTotal = number_format(
            $cartTotal,
            2,
            '.',
            ''
        );

        $checkoutError =
            $_SESSION['checkout_error'] ?? '';

        unset($_SESSION['checkout_error']);

        require __DIR__ . "/../views/checkout/index.php";
    }

    /**
     * Process the checkout request.
     */
    public function process(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../checkout.php");
            exit;
        }

        $cartItems = $this->getCartItems();

        if (empty($cartItems)) {
            $_SESSION['checkout_error'] =
                "Your cart is empty.";

            header("Location: ../cart.php");
            exit;
        }

        /*
         * Use the logged-in user's account when available.
         */
        if (isset($_SESSION['user_id'])) {

            $userId = (int) $_SESSION['user_id'];

            $user = $this->userModel->getById($userId);

            if (!$user) {
                unset(
                    $_SESSION['user_id'],
                    $_SESSION['user_first_name'],
                    $_SESSION['user_last_name'],
                    $_SESSION['user_email']
                );

                $_SESSION['checkout_error'] =
                    "Your account could not be found. Please log in again.";

                header("Location: ../login.php");
                exit;
            }

        } else {

            /*
             * Guest checkout remains supported.
             */
            $firstName =
                trim($_POST['first_name'] ?? '');

            $lastName =
                trim($_POST['last_name'] ?? '');

            $email =
                trim($_POST['email'] ?? '');

            if (
                $firstName === '' ||
                $lastName === '' ||
                $email === ''
            ) {
                $_SESSION['checkout_error'] =
                    "Please complete all required fields.";

                header("Location: ../checkout.php");
                exit;
            }

            if (strlen($firstName) > 50) {
                $_SESSION['checkout_error'] =
                    "First name is too long.";

                header("Location: ../checkout.php");
                exit;
            }

            if (strlen($lastName) > 50) {
                $_SESSION['checkout_error'] =
                    "Last name is too long.";

                header("Location: ../checkout.php");
                exit;
            }

            if (strlen($email) > 150) {
                $_SESSION['checkout_error'] =
                    "Email address is too long.";

                header("Location: ../checkout.php");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['checkout_error'] =
                    "Please enter a valid email address.";

                header("Location: ../checkout.php");
                exit;
            }

            $user =
                $this->userModel->getByEmail($email);

            if ($user) {

                $userId =
                    (int) $user['user_id'];

            } else {

                /*
                 * Preserve the existing guest-checkout behavior.
                 *
                 * A temporary password is generated because
                 * the users table requires a password_hash.
                 */
                $temporaryPassword =
                    bin2hex(random_bytes(16));

                $passwordHash =
                    password_hash(
                        $temporaryPassword,
                        PASSWORD_DEFAULT
                    );

                try {

                    $userId =
                        $this->userModel->create(
                            $firstName,
                            $lastName,
                            $email,
                            $passwordHash
                        );

                } catch (PDOException $e) {

                    $_SESSION['checkout_error'] =
                        "Unable to create the customer account.";

                    header("Location: ../checkout.php");
                    exit;
                }
            }
        }

        try {

            $this->pdo->beginTransaction();

            /*
             * Re-check inventory while the transaction is active.
             */
            $validatedItems = [];
            $orderTotal = 0.00;

            foreach ($cartItems as $item) {

                $productId =
                    (int) $item['product_id'];

                $requestedQuantity =
                    (int) $item['quantity'];

                $sql = "SELECT
                            product_id,
                            product_name,
                            price,
                            quantity_available
                        FROM products
                        WHERE product_id = :product_id
                        FOR UPDATE";

                $stmt =
                    $this->pdo->prepare($sql);

                $stmt->execute([
                    ':product_id' => $productId
                ]);

                $product =
                    $stmt->fetch();

                if (!$product) {
                    throw new Exception(
                        "One of the products in your cart no longer exists."
                    );
                }

                $availableQuantity =
                    (int) $product['quantity_available'];

                if (
                    $requestedQuantity <= 0 ||
                    $requestedQuantity > $availableQuantity
                ) {
                    throw new Exception(
                        "Not enough inventory is available for " .
                        $product['product_name'] . "."
                    );
                }

                $price =
                    (float) $product['price'];

                $subtotal =
                    $price * $requestedQuantity;

                $orderTotal += $subtotal;

                $validatedItems[] = [
                    'product_id' =>
                        $productId,

                    'product_name' =>
                        $product['product_name'],

                    'quantity' =>
                        $requestedQuantity,

                    'price' =>
                        $price,

                    'subtotal' =>
                        $subtotal
                ];
            }

            /*
             * Create the order.
             */
            $orderId =
                $this->orderModel->create(
                    $userId,
                    $orderTotal
                );

            /*
             * Create order items and decrease inventory.
             */
            foreach ($validatedItems as $item) {

                $this->orderItemModel->create(
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );

                $updateSql = "UPDATE products
                              SET quantity_available =
                                  quantity_available - :quantity
                              WHERE product_id = :product_id";

                $updateStmt =
                    $this->pdo->prepare($updateSql);

                $updateStmt->execute([
                    ':quantity' =>
                        $item['quantity'],

                    ':product_id' =>
                        $item['product_id']
                ]);
            }

            $this->pdo->commit();

            /*
             * Clear the cart after successful checkout.
             */
            $_SESSION['cart'] = [];

            /*
             * Store the order ID for the confirmation page.
             */
            $_SESSION['last_order_id'] =
                $orderId;

            header("Location: ../confirmation.php");
            exit;

        } catch (Exception $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            $_SESSION['checkout_error'] =
                $e->getMessage();

            header("Location: ../checkout.php");
            exit;
        }
    }

    /**
     * Get the current cart with current product information.
     */
    private function getCartItems(): array
    {
        $cart =
            $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            return [];
        }

        $productIds =
            array_keys($cart);

        $placeholders =
            implode(
                ',',
                array_fill(
                    0,
                    count($productIds),
                    '?'
                )
            );

        $sql = "SELECT
                    product_id,
                    product_name,
                    description,
                    price,
                    quantity_available,
                    image
                FROM products
                WHERE product_id IN ($placeholders)
                ORDER BY product_name";

        $stmt =
            $this->pdo->prepare($sql);

        $stmt->execute($productIds);

        $products =
            $stmt->fetchAll();

        $cartItems = [];

        foreach ($products as $product) {

            $productId =
                (int) $product['product_id'];

            $quantity =
                (int) ($cart[$productId] ?? 0);

            if ($quantity <= 0) {
                continue;
            }

            $availableQuantity =
                (int) $product['quantity_available'];

            if ($quantity > $availableQuantity) {
                $quantity =
                    $availableQuantity;
            }

            if ($quantity <= 0) {
                continue;
            }

            $price =
                (float) $product['price'];

            $subtotal =
                $price * $quantity;

            $cartItems[] = [
                'product_id' =>
                    $productId,

                'product_name' =>
                    $product['product_name'],

                'description' =>
                    $product['description'],

                'price' =>
                    $price,

                'quantity' =>
                    $quantity,

                'subtotal' =>
                    $subtotal,

                'quantity_available' =>
                    $availableQuantity,

                'image' =>
                    $product['image']
            ];
        }

        return $cartItems;
    }
}