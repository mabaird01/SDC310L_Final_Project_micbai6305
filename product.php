<?php

require_once __DIR__ . "/controllers/ProductController.php";

$controller = new ProductController();

$productId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$productId) {
    http_response_code(400);

    echo "Invalid product ID.";
    exit;
}

$controller->show($productId);
?>