<?php

session_start();

require_once __DIR__ . "/../controllers/CheckoutController.php";

$controller = new CheckoutController();

$controller->process();
