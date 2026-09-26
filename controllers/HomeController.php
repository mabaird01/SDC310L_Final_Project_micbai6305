<?php

require_once __DIR__ . "/../php/config.php";
require_once __DIR__ . "/../php/database.php";
require_once __DIR__ . "/../models/Product.php";

class HomeController
{
    private Product $productModel;

    public function __construct()
    {
        global $pdo;

        $this->productModel = new Product($pdo);
    }

    public function index(): void
    {
        $products = $this->productModel->getAll();

        // Display a small selection of products on the home page.
        $featuredProducts = array_slice($products, 0, 4);

        require __DIR__ . "/../views/home/index.php";
    }
}