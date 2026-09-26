<?php

require_once __DIR__ . "/../php/database.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/OrderItem.php";

class OrderController
{
    private Order $orderModel;
    private OrderItem $orderItemModel;

    public function __construct()
    {
        global $pdo;

        $this->orderModel = new Order($pdo);
        $this->orderItemModel = new OrderItem($pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /**
     * Display the confirmation page for the
     * most recently completed order.
     */
    public function confirmation(): void
    {
        $orderId = $_SESSION['last_order_id'] ?? null;

        if (
            !$orderId ||
            !filter_var($orderId, FILTER_VALIDATE_INT)
        ) {
            header("Location: products.php");
            exit;
        }

        $orderId = (int) $orderId;

        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            unset($_SESSION['last_order_id']);

            http_response_code(404);

            echo "Order not found.";

            exit;
        }

        $orderItems =
            $this->orderItemModel->getByOrderId(
                $orderId
            );

        if (empty($orderItems)) {
            unset($_SESSION['last_order_id']);

            http_response_code(404);

            echo "Order items not found.";

            exit;
        }

        /*
         * The confirmation page is intended to be viewed
         * only once after checkout.
         */
        unset($_SESSION['last_order_id']);

        require __DIR__ . "/../views/checkout/confirmation.php";
    }


    /**
     * Display a specific order belonging to
     * the currently logged-in user.
     */
    public function show(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        $orderId = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$orderId) {
            http_response_code(400);

            echo "Invalid order ID.";

            exit;
        }

        $userId = (int) $_SESSION['user_id'];

        /*
         * Retrieve the order using BOTH the order ID
         * and logged-in user's ID.
         *
         * This prevents a user from viewing another
         * user's order by changing ?id= in the URL.
         */
        $order =
            $this->orderModel->getByIdForUser(
                (int) $orderId,
                $userId
            );

        if (!$order) {
            http_response_code(404);

            echo "Order not found.";

            exit;
        }

        $orderItems =
            $this->orderItemModel->getByOrderId(
                (int) $orderId
            );

        require __DIR__ . "/../views/account/order.php";
    }
}