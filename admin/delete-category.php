<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: categories.php');
    exit;
}

$categoryId = (int)($_POST['id'] ?? 0);

if ($categoryId < 1) {
    header('Location: categories.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Check Category Exists
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id
    FROM categories
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    'id' => $categoryId
]);

if (!$stmt->fetch()) {
    header('Location: categories.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Prevent Delete When Products Exist
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM products
    WHERE category_id = :category_id
");

$stmt->execute([
    'category_id' => $categoryId
]);

$productCount = (int)$stmt->fetchColumn();

if ($productCount > 0) {

    header(
        'Location: categories.php?delete=blocked'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    DELETE FROM categories
    WHERE id = :id
");

$stmt->execute([
    'id' => $categoryId
]);

header(
    'Location: categories.php?deleted=1'
);

exit;