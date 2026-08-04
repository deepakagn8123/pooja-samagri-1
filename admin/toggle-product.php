<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$productId = (int)($_POST['id'] ?? 0);

if ($productId < 1) {
    header('Location: products.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Current Status
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, is_active
    FROM products
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    'id' => $productId
]);

$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Toggle Status
|--------------------------------------------------------------------------
*/

$newStatus = $product['is_active'] ? 0 : 1;

$stmt = $pdo->prepare("
    UPDATE products
    SET is_active = :is_active
    WHERE id = :id
");

$stmt->execute([
    'is_active' => $newStatus,
    'id' => $productId
]);

header('Location: products.php?status=updated');
exit;