<?php

require_once __DIR__ . "/../php/database.php";

class CartController
{
    private PDO $pdo;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index(): void
    {
        $cart = $_SESSION['cart'];

        $cartItems = [];
        $cartTotal = 0.00;

        if (!empty($cart)) {

            $productIds = array_keys($cart);

            $placeholders = implode(
                ',',
                array_fill(0, count($productIds), '?')
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

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute($productIds);

            $products = $stmt->fetchAll();

            foreach ($products as $product) {

                $productId = (int) $product['product_id'];

                $quantity = (int) (
                    $cart[$productId] ?? 0
                );

                if ($quantity <= 0) {
                    continue;
                }

                $availableQuantity =
                    (int) $product['quantity_available'];

                /*
                 * Prevent the cart from displaying
                 * more items than are currently in stock.
                 */
                if ($quantity > $availableQuantity) {
                    $quantity = $availableQuantity;
                }

                if ($quantity <= 0) {
                    continue;
                }

                $price = (float) $product['price'];

                $subtotal = $price * $quantity;

                $cartTotal += $subtotal;

                $cartItems[] = [
                    'product_id' => $productId,
                    'product_name' => $product['product_name'],
                    'description' => $product['description'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'quantity_available' => $availableQuantity,
                    'image' => $product['image']
                ];
            }
        }

        $cartTotal = number_format(
            $cartTotal,
            2,
            '.',
            ''
        );

        require __DIR__ . "/../views/cart/index.php";
    }
}