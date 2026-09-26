<?php

require_once __DIR__ . "/../php/database.php";
require_once __DIR__ . "/../models/Product.php";

class ProductController
{
    private Product $productModel;

    public function __construct()
    {
        global $pdo;

        $this->productModel = new Product($pdo);
    }


    /**
     * Display all products.
     */
    public function index(): void
    {
        $products = $this->productModel->getAll();

        require __DIR__ . "/../views/products/index.php";
    }


    /**
     * Display one product.
     *
     * @param int $productId
     */
    public function show(int $productId): void
    {
        $product = $this->productModel->getById($productId);

        if (!$product) {
            http_response_code(404);

            $message = "Product not found.";

            require __DIR__ . "/../views/products/show.php";
            return;
        }

        require __DIR__ . "/../views/products/show.php";
    }
}