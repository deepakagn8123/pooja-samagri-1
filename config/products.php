<?php

function getAllProducts(PDO $pdo): array
{
    $sql = "
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1
        ORDER BY p.id ASC
    ";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll();
}


function getProductBySlug(PDO $pdo, string $slug): ?array
{
    $sql = "
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE p.slug = :slug
        AND p.is_active = 1
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'slug' => $slug
    ]);

    $product = $stmt->fetch();

    return $product ?: null;
}