<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();


/*
|--------------------------------------------------------------------------
| Only POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    exit('Method not allowed.');
}


/*
|--------------------------------------------------------------------------
| CSRF protection
|--------------------------------------------------------------------------
*/

verify_csrf();

/*
|--------------------------------------------------------------------------
| Validate order ID
|--------------------------------------------------------------------------
*/

$orderId = filter_var(
    $_POST['order_id'] ?? null,
    FILTER_VALIDATE_INT
);


if (!$orderId || $orderId < 1) {

    http_response_code(400);

    exit('Invalid order.');
}


/*
|--------------------------------------------------------------------------
| Validate status
|--------------------------------------------------------------------------
*/

$allowedStatuses = [
    'new',
    'incomplete',
    'payment_pending',
    'processing',
    'completed',
    'cancelled'
];


$status = $_POST['status'] ?? '';


if (!in_array($status, $allowedStatuses, true)) {

    http_response_code(400);

    exit('Invalid status.');
}


/*
|--------------------------------------------------------------------------
| Make sure order exists
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id
    FROM orders
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([
    $orderId
]);


if (!$stmt->fetch()) {

    http_response_code(404);

    exit('Order not found.');
}


/*
|--------------------------------------------------------------------------
| Update status
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    UPDATE orders
    SET
        status = ?,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");


$stmt->execute([
    $status,
    $orderId
]);


/*
|--------------------------------------------------------------------------
| Return to order
|--------------------------------------------------------------------------
*/

header(
    'Location: order-view.php?id=' .
    $orderId
);

exit;