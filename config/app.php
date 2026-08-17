<?php

/*
|--------------------------------------------------------------------------
| Secure Session Configuration
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {

    /*
    |--------------------------------------------------------------------------
    | Detect HTTPS
    |--------------------------------------------------------------------------
    |
    | Secure cookies must be enabled in production HTTPS.
    | This detection also allows local HTTP development.
    |
    */

    $isHttps = (
        !empty($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== 'off'
    );

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */

    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_trans_sid', '0');

    ini_set('session.cookie_httponly', '1');
    ini_set(
        'session.cookie_secure',
        $isHttps ? '1' : '0'
    );

    ini_set('session.cookie_samesite', 'Lax');

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Parameters
    |--------------------------------------------------------------------------
    */

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Start Session
    |--------------------------------------------------------------------------
    */

    session_start();
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/products.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';

function searchProducts(PDO $pdo, string $keyword): array
{
    $keyword = trim($keyword);

    if ($keyword === '') {
        return [];
    }

    // Split search into individual words
    $words = preg_split('/\s+/', $keyword);

    $where = [];
    $params = [];

    foreach ($words as $index => $word) {

        $param = ":word{$index}";

        $where[] = "(
            p.name LIKE $param
            OR p.description LIKE $param
            OR p.tag LIKE $param
            OR c.name LIKE $param
        )";

        $params["word{$index}"] = "%{$word}%";
    }

    $sql = "
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c
            ON c.id = p.category_id
        WHERE
            p.is_active = 1
            AND
            " . implode(" AND ", $where) . "
        ORDER BY
            p.name ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function getCategoryBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM categories
        WHERE slug = :slug
        LIMIT 1
    ");

    $stmt->execute([
        'slug' => $slug
    ]);

    $category = $stmt->fetch();

    return $category ?: null;
}