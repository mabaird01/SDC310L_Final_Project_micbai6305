<?php

require_once __DIR__ . "/../php/database.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/OrderItem.php";

class AccountController
{
    private User $userModel;
    private Order $orderModel;
    private OrderItem $orderItemModel;

    public function __construct()
    {
        global $pdo;

        $this->userModel = new User($pdo);
        $this->orderModel = new Order($pdo);
        $this->orderItemModel = new OrderItem($pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        $userId = (int) $_SESSION['user_id'];

        $user = $this->userModel->getById($userId);

        if (!$user) {
            $_SESSION = [];

            header("Location: login.php");
            exit;
        }

        /*
         * Get this user's orders through the Order model.
         */
        $orders = $this->orderModel->getByUserId($userId);

        /*
         * Load the items belonging to each order.
         */
        foreach ($orders as &$order) {

            $order['items'] =
                $this->orderItemModel->getByOrderId(
                    (int) $order['order_id']
                );
        }

        unset($order);

        require __DIR__ . "/../views/account/index.php";
    }
}