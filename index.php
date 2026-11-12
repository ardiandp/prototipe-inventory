<?php
// Root entrypoint for Laragon Apache / Nginx
// Redirects requests to the Laravel public directory of inventory-prototipe
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (str_contains($requestUri, 'inventory-prototipe/public')) {
    require_once __DIR__ . '/inventory-prototipe/public/index.php';
} else {
    header("Location: ./inventory-prototipe/public/");
    exit;
}
