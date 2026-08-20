<?php

require_once __DIR__ . '/app.php';

header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}


/*
 * Read JSON request.
 */
$rawBody = file_get_contents('php://input');


try {

    $data = json_decode(
        $rawBody,
        true,
        32,
        JSON_THROW_ON_ERROR
    );

} catch (JsonException $e) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);

    exit;
}


/*
 * Basic request validation.
 */
$cart = $data['cart'] ?? [];

$name = trim(
    (string)($data['name'] ?? '')
);

$phone = trim(
    (string)($data['phone'] ?? '')
);

$address = trim(
    (string)($data['address'] ?? '')
);

$callBeforeDelivery =
    !empty($data['call_before_delivery']);

$note = trim(
    (string)($data['note'] ?? '')
);


if (!is_array($cart) || count($cart) < 1) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Your cart is empty.'
    ]);

    exit;
}


if (count($cart) > 50) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Cart contains too many items.'
    ]);

    exit;
}


/*
 * Customer validation.
 */
if (
    $name === '' ||
    mb_strlen($name) < 2 ||
    mb_strlen($name) > 100
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid name.'
    ]);

    exit;
}


if (!preg_match('/^[0-9]{10}$/', $phone)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid 10-digit mobile number.'
    ]);

    exit;
}


if (
    $address === '' ||
    mb_strlen($address) < 5 ||
    mb_strlen($address) > 500
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid delivery address.'
    ]);

    exit;
}


if (
    $note !== '' &&
    mb_strlen($note) > 500
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Order note is too long.'
    ]);

    exit;
}


/*
 * Build requested product quantities.
 */
$requestedProducts = [];


foreach ($cart as $item) {

    if (!is_array($item)) {
        continue;
    }


    $slug = trim(
        (string)($item['slug'] ?? '')
    );


    $qty = filter_var(
        $item['qty'] ?? null,
        FILTER_VALIDATE_INT
    );


    if (
        $slug === '' ||
        !valid_slug($slug) ||
        $qty === false ||
        $qty < 1
    ) {
        continue;
    }


    $qty = min($qty, 100);


    if (isset($requestedProducts[$slug])) {

        $requestedProducts[$slug] += $qty;

    } else {

        $requestedProducts[$slug] = $qty;
    }
}


if (!$requestedProducts) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'No valid products found.'
    ]);

    exit;
}


/*
 * Get the REAL product data from the database.
 *
 * Prices are NEVER trusted from the browser.
 */
$slugs = array_keys(
    $requestedProducts
);


$placeholders = implode(
    ',',
    array_fill(
        0,
        count($slugs),
        '?'
    )
);


$sql = "
    SELECT
        id,
        name,
        slug,
        price,
        unit
    FROM products
    WHERE slug IN ($placeholders)
      AND is_active = 1
";


$stmt = $pdo->prepare($sql);

$stmt->execute($slugs);

$products = $stmt->fetchAll();


/*
 * Make sure every requested product still exists.
 */
if (count($products) !== count($requestedProducts)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'One or more products are no longer available. Please refresh your cart.'
    ]);

    exit;
}


/*
 * Calculate everything from database values.
 */
$items = [];

$subtotal = 0;


foreach ($products as $product) {

    $slug = $product['slug'];

    $qty = $requestedProducts[$slug];

    $price = (float)$product['price'];

    $lineTotal = round(
        $price * $qty,
        2
    );


    $subtotal += $lineTotal;


    $items[] = [

        'product_id' => (int)$product['id'],

        'name' => $product['name'],

        'slug' => $slug,

        'price' => $price,

        'unit' => $product['unit'] ?? '',

        'qty' => $qty,

        'line_total' => $lineTotal

    ];
}


$subtotal = round(
    $subtotal,
    2
);


/*
 * Generate human-readable order number.
 */
$orderNumber =
    'NR-' .
    date('Ymd-His') .
    '-' .
    strtoupper(
        bin2hex(
            random_bytes(3)
        )
    );


try {

    $pdo->beginTransaction();


    /*
     * Create main order.
     */
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            order_number,
            customer_name,
            customer_phone,
            customer_address,
            call_before_delivery,
            customer_note,
            subtotal,
            status
        )
        VALUES (
            :order_number,
            :customer_name,
            :customer_phone,
            :customer_address,
            :call_before_delivery,
            :customer_note,
            :subtotal,
            'new'
        )
    ");


    $stmt->execute([

        ':order_number' =>
            $orderNumber,

        ':customer_name' =>
            $name,

        ':customer_phone' =>
            $phone,

        ':customer_address' =>
            $address,

        ':call_before_delivery' =>
            $callBeforeDelivery ? 1 : 0,

        ':customer_note' =>
            $note !== '' ? $note : null,

        ':subtotal' =>
            $subtotal

    ]);


    $orderId =
        (int)$pdo->lastInsertId();


    /*
     * Save product snapshots.
     */
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items (
            order_id,
            product_id,
            product_name,
            product_slug,
            unit_price,
            quantity,
            line_total
        )
        VALUES (
            :order_id,
            :product_id,
            :product_name,
            :product_slug,
            :unit_price,
            :quantity,
            :line_total
        )
    ");


    foreach ($items as $item) {

        $itemStmt->execute([

            ':order_id' =>
                $orderId,

            ':product_id' =>
                $item['product_id'],

            ':product_name' =>
                $item['name'],

            ':product_slug' =>
                $item['slug'],

            ':unit_price' =>
                $item['price'],

            ':quantity' =>
                $item['qty'],

            ':line_total' =>
                $item['line_total']

        ]);
    }


    $pdo->commit();


    /*
     * Return the same verified data
     * that will be used for WhatsApp.
     */
    echo json_encode([

        'success' => true,

        'order_id' => $orderId,

        'order_number' =>
            $orderNumber,

        'customer' => [

            'name' => $name,

            'phone' => $phone,

            'address' => $address,

            'call_before_delivery' =>
                $callBeforeDelivery,

            'note' => $note

        ],

        'items' => $items,

        'subtotal' => $subtotal

    ]);


} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to create order. Please try again.'
    ]);

    exit;
}