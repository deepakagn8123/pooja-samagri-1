<?php

function getAllProducts(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1
        ORDER BY p.id DESC
    ");

    return $stmt->fetchAll();
}


function getProductBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE p.slug = :slug
          AND p.is_active = 1
        LIMIT 1
    ");

    $stmt->execute([
        'slug' => $slug
    ]);

    $product = $stmt->fetch();

    return $product ?: null;
}


function getAllCategories(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT
            id,
            name,
            slug
        FROM categories
        ORDER BY name ASC
    ");

    return $stmt->fetchAll();
}


function getProductsByCategory(PDO $pdo, string $categorySlug): array
{
    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE c.slug = :category_slug
          AND p.is_active = 1
        ORDER BY p.id DESC
    ");

    $stmt->execute([
        'category_slug' => $categorySlug
    ]);

    return $stmt->fetchAll();
}


function getRelatedProducts(
    PDO $pdo,
    int $categoryId,
    int $currentProductId,
    int $limit = 4
): array
{
    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE p.category_id = :category_id
          AND p.id != :current_product_id
          AND p.is_active = 1
        ORDER BY p.id DESC
        LIMIT :product_limit
    ");

    $stmt->bindValue(
        ':category_id',
        $categoryId,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':current_product_id',
        $currentProductId,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':product_limit',
        $limit,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll();
}