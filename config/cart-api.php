<?php

require_once __DIR__ . '/app.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);

    exit;
}

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$cart = $data['cart'] ?? [];

if (!is_array($cart)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid cart'
    ]);

    exit;
}


$requestedProducts = [];

foreach ($cart as $item) {

    $slug = trim((string)($item['slug'] ?? ''));
    $qty = (int)($item['qty'] ?? 0);

    if ($slug === '' || $qty < 1) {
        continue;
    }

    /*
     * Limit quantities here too.
     * Browser data must never be trusted.
     */
    $qty = min($qty, 100);

    if (isset($requestedProducts[$slug])) {
        $requestedProducts[$slug] += $qty;
    } else {
        $requestedProducts[$slug] = $qty;
    }
}


if (!$requestedProducts) {

    echo json_encode([
        'success' => true,
        'items' => [],
        'subtotal' => 0
    ]);

    exit;
}


$slugs = array_keys($requestedProducts);

$placeholders = implode(
    ',',
    array_fill(0, count($slugs), '?')
);


$sql = "
    SELECT
        id,
        name,
        slug,
        price,
        unit,
        image
    FROM products
    WHERE slug IN ($placeholders)
      AND is_active = 1
";


$stmt = $pdo->prepare($sql);
$stmt->execute($slugs);

$products = $stmt->fetchAll();


$items = [];
$subtotal = 0;


foreach ($products as $product) {

    $qty = $requestedProducts[$product['slug']];

    $price = (float)$product['price'];

    $lineTotal = $price * $qty;

    $subtotal += $lineTotal;


    $items[] = [

        'id' => (int)$product['id'],

        'name' => $product['name'],

        'slug' => $product['slug'],

        'price' => $price,

        'unit' => $product['unit'] ?? '',

        'image' => $product['image'] ?? null,

        'qty' => $qty,

        'line_total' => $lineTotal

    ];
}


echo json_encode([
    'success' => true,
    'items' => $items,
    'subtotal' => $subtotal
]);