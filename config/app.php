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

        $nameParam = ":name{$index}";
        $descriptionParam = ":description{$index}";
        $tagParam = ":tag{$index}";
        $categoryParam = ":category{$index}";

        $where[] = "(
            p.name LIKE {$nameParam}
            OR p.description LIKE {$descriptionParam}
            OR p.tag LIKE {$tagParam}
            OR c.name LIKE {$categoryParam}
        )";

        $searchValue = "%{$word}%";

        $params["name{$index}"] = $searchValue;
        $params["description{$index}"] = $searchValue;
        $params["tag{$index}"] = $searchValue;
        $params["category{$index}"] = $searchValue;
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


function js_json($value): string
{
    return json_encode(
        $value,
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT |
        JSON_UNESCAPED_UNICODE
    );
}


function request_string($value, string $default = ''): string
{
    if (!is_string($value)) {
        return $default;
    }

    return trim($value);
}


function valid_slug(string $slug, int $max = 180): bool
{
    if ($slug === '' || mb_strlen($slug) > $max) {
        return false;
    }

    return preg_match(
        '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        $slug
    ) === 1;
}


function apply_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    // Basic XSS protection for older browsers.
    header('X-XSS-Protection: 1; mode=block');
}

apply_security_headers();