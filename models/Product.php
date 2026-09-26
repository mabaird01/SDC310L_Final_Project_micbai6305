<?php

class Product
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Get all products.
     */
    public function getAll(): array
    {
        $sql = "SELECT
                    product_id,
                    product_name,
                    description,
                    price,
                    quantity_available,
                    image
                FROM products
                ORDER BY product_name";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * Get one product by ID.
     */
    public function getById(
        int $productId
    ): array|false {

        $sql = "SELECT
                    product_id,
                    product_name,
                    description,
                    price,
                    quantity_available,
                    image
                FROM products
                WHERE product_id = :product_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':product_id' => $productId
        ]);

        return $stmt->fetch();
    }
}