<?php

class OrderItem
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Create an order item.
     */
    public function create(
        int $orderId,
        int $productId,
        int $quantity,
        float $price
    ): int {
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

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
            ':price' => $price
        ]);

        return (int) $this->pdo->lastInsertId();
    }


    /**
     * Get all items belonging to an order.
     */
    public function getByOrderId(int $orderId): array
    {
        $sql = "SELECT
                    oi.order_item_id,
                    oi.order_id,
                    oi.product_id,
                    oi.quantity,
                    oi.price,
                    p.product_name,
                    p.image
                FROM order_items oi
                INNER JOIN products p
                    ON oi.product_id = p.product_id
                WHERE oi.order_id = :order_id
                ORDER BY oi.order_item_id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':order_id' => $orderId
        ]);

        return $stmt->fetchAll();
    }
}