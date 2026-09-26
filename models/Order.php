<?php

class Order
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Create a new order.
     */
    public function create(int $userId, float $total): int
    {
        $sql = "INSERT INTO orders
                    (user_id, total)
                VALUES
                    (:user_id, :total)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId,
            ':total' => $total
        ]);

        return (int) $this->pdo->lastInsertId();
    }


    /**
     * Get an order by its ID.
     */
    public function getById(int $orderId): array|false
    {
        $sql = "SELECT
                    order_id,
                    user_id,
                    order_date,
                    total
                FROM orders
                WHERE order_id = :order_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':order_id' => $orderId
        ]);

        return $stmt->fetch();
    }


    /**
     * Get an order belonging to a specific user.
     */
    public function getByIdForUser(
        int $orderId,
        int $userId
    ): array|false {

        $sql = "SELECT
                    order_id,
                    user_id,
                    order_date,
                    total
                FROM orders
                WHERE order_id = :order_id
                  AND user_id = :user_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':order_id' => $orderId,
            ':user_id' => $userId
        ]);

        return $stmt->fetch();
    }


    /**
     * Get all orders belonging to a user.
     */
    public function getByUserId(int $userId): array
    {
        $sql = "SELECT
                    order_id,
                    user_id,
                    order_date,
                    total
                FROM orders
                WHERE user_id = :user_id
                ORDER BY order_date DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll();
    }
}