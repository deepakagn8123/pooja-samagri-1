<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();


/*
|--------------------------------------------------------------------------
| Get Order ID
|--------------------------------------------------------------------------
*/

$orderId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$orderId || $orderId < 1) {
    http_response_code(400);
    exit('Invalid order.');
}


/*
|--------------------------------------------------------------------------
| Get Order
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        order_number,
        customer_name,
        customer_phone,
        customer_address,
        call_before_delivery,
        customer_note,
        subtotal,
        status,
        created_at,
        updated_at
    FROM orders
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([
    $orderId
]);

$order = $stmt->fetch();


if (!$order) {
    http_response_code(404);
    exit('Order not found.');
}


/*
|--------------------------------------------------------------------------
| Get Order Items
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        product_id,
        product_name,
        product_slug,
        unit_price,
        quantity,
        line_total
    FROM order_items
    WHERE order_id = ?
    ORDER BY id ASC
");

$stmt->execute([
    $orderId
]);

$items = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Allowed statuses
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


function e($value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


function statusLabel(string $status): string
{
    return match ($status) {

        'new' =>
            'New',

        'incomplete' =>
            'Incomplete',

        'payment_pending' =>
            'Payment Pending',

        'processing' =>
            'Processing',

        'completed' =>
            'Completed',

        'cancelled' =>
            'Cancelled',

        default =>
            ucfirst($status)
    };
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Order <?= e($order['order_number']) ?> —
    Nitya Ritual E-Store
</title>


<style>

* {
    box-sizing: border-box;
}


body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f6f6f6;
    color: #222;
}


/* Sidebar */

.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: #8B1E1E;
    color: white;
}


.sidebar-brand {
    padding: 25px 20px;
    font-size: 20px;
    font-weight: bold;
    border-bottom: 1px solid rgba(255,255,255,.15);
}


.sidebar nav {
    padding: 20px 0;
}


.sidebar a {
    display: block;
    padding: 13px 20px;
    color: white;
    text-decoration: none;
}


.sidebar a:hover,
.sidebar a.active {
    background: rgba(255,255,255,.12);
}


/* Main */

.main {
    margin-left: 240px;
    min-height: 100vh;
}


/* Header */

.topbar {
    background: white;
    padding: 18px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
}


.topbar h2 {
    margin: 0;
}


.admin-info {
    display: flex;
    align-items: center;
    gap: 20px;
}


/* Content */

.content {
    padding: 30px;
}


.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #8B1E1E;
    text-decoration: none;
    font-weight: 600;
}


/* Header */

.order-header {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 20px;
}


.order-header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}


.order-number {
    margin: 0 0 7px;
    font-size: 24px;
}


.order-date {
    color: #777;
    font-size: 14px;
}


/* Status */

.status {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}


.status-new {
    background: #e7f0ff;
    color: #1d5dbf;
}


.status-incomplete {
    background: #fff0df;
    color: #a45b00;
}


.status-payment_pending {
    background: #fff8d9;
    color: #8a6d00;
}


.status-processing {
    background: #eee5ff;
    color: #6840a5;
}


.status-completed {
    background: #e4f7e8;
    color: #1d7a35;
}


.status-cancelled {
    background: #fde8e8;
    color: #b42318;
}


/* Grid */

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}


/* Card */

.card {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    overflow: hidden;
}


.card-header {
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
}


.card-header h3 {
    margin: 0;
}


.card-body {
    padding: 20px;
}


/* Customer */

.detail {
    margin-bottom: 15px;
}


.detail:last-child {
    margin-bottom: 0;
}


.detail-label {
    display: block;
    color: #777;
    font-size: 12px;
    margin-bottom: 4px;
}


.detail-value {
    font-weight: 500;
    line-height: 1.5;
}


/* Items */

.item {
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}


.item:first-child {
    padding-top: 0;
}


.item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
}


.item-top {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}


.item-name {
    font-weight: 600;
}


.item-meta {
    margin-top: 5px;
    color: #777;
    font-size: 13px;
}


.item-total {
    font-weight: 600;
    white-space: nowrap;
}


/* Total */

.order-total {
    margin-top: 20px;
    padding-top: 18px;
    border-top: 2px solid #eee;
    display: flex;
    justify-content: space-between;
    font-size: 20px;
    font-weight: bold;
}


/* Status form */

.status-form {
    margin-top: 20px;
}


.status-form label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
}


.status-form select {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font: inherit;
    background: white;
}


.update-btn {
    width: 100%;
    margin-top: 12px;
    padding: 11px 15px;
    border: 0;
    border-radius: 7px;
    background: #8B1E1E;
    color: white;
    font: inherit;
    font-weight: 600;
    cursor: pointer;
}


.update-btn:hover {
    opacity: .9;
}


/* Responsive */

@media (max-width: 900px) {

    .sidebar {
        width: 200px;
    }

    .main {
        margin-left: 200px;
    }

    .grid {
        grid-template-columns: 1fr;
    }

    .content {
        padding: 20px;
    }

}


</style>

</head>


<body>


<div class="sidebar">

    <div class="sidebar-brand">
        Nitya Ritual E-Store
    </div>


    <nav>

        <a href="index.php">
            Dashboard
        </a>

        <a href="products.php">
            Products
        </a>

        <!-- <a href="orders.php" class="active">
            Orders
        </a> -->

        <a href="#">
            Add Product
        </a>

        <a href="categories.php">
            Categories
        </a>

        <a href="settings.php">
            Security Settings
        </a>

        <a
            href="../index.php"
            target="_blank"
        >
            View Website
        </a>

    </nav>

</div>


<div class="main">


    <div class="topbar">

        <h2>
            Order Details
        </h2>


        <div class="admin-info">

            <span>
                <?= e(
                    $_SESSION['admin_name'] ?? 'Admin'
                ) ?>
            </span>


            <form
                method="POST"
                action="logout.php"
            >

                <?= csrf_field() ?>

                <button type="submit">
                    Logout
                </button>

            </form>

        </div>

    </div>


    <div class="content">


        <a
            href="orders.php"
            class="back-link"
        >
            ← Back to Orders
        </a>


        <div class="order-header">

            <div class="order-header-top">


                <div>

                    <h1 class="order-number">

                        <?= e(
                            $order['order_number']
                        ) ?>

                    </h1>


                    <div class="order-date">

                        Placed on
                        <?= e(
                            date(
                                'd M Y, h:i A',
                                strtotime(
                                    $order['created_at']
                                )
                            )
                        ) ?>

                    </div>

                </div>


                <span
                    class="status status-<?= e(
                        $order['status']
                    ) ?>"
                >
                    <?= e(
                        statusLabel(
                            $order['status']
                        )
                    ) ?>
                </span>


            </div>

        </div>


        <div class="grid">


            <!-- Customer -->

            <div class="card">

                <div class="card-header">

                    <h3>
                        Customer Details
                    </h3>

                </div>


                <div class="card-body">


                    <div class="detail">

                        <span class="detail-label">
                            Name
                        </span>

                        <div class="detail-value">
                            <?= e(
                                $order['customer_name']
                            ) ?>
                        </div>

                    </div>


                    <div class="detail">

                        <span class="detail-label">
                            Mobile
                        </span>

                        <div class="detail-value">
                            <?= e(
                                $order['customer_phone']
                            ) ?>
                        </div>

                    </div>


                    <div class="detail">

                        <span class="detail-label">
                            Delivery Address
                        </span>

                        <div class="detail-value">
                            <?= nl2br(
                                e(
                                    $order['customer_address']
                                )
                            ) ?>
                        </div>

                    </div>


                    <div class="detail">

                        <span class="detail-label">
                            Call Before Delivery
                        </span>

                        <div class="detail-value">

                            <?= $order['call_before_delivery']
                                ? 'Yes'
                                : 'No'
                            ?>

                        </div>

                    </div>


                    <?php if (
                        !empty(
                            $order['customer_note']
                        )
                    ): ?>

                        <div class="detail">

                            <span class="detail-label">
                                Customer Note
                            </span>

                            <div class="detail-value">

                                <?= nl2br(
                                    e(
                                        $order['customer_note']
                                    )
                                ) ?>

                            </div>

                        </div>

                    <?php endif; ?>


                </div>

            </div>


            <!-- Order -->

            <div class="card">

                <div class="card-header">

                    <h3>
                        Order Items
                    </h3>

                </div>


                <div class="card-body">


                    <?php foreach ($items as $item): ?>

                        <div class="item">


                            <div class="item-top">


                                <div>

                                    <div class="item-name">

                                        <?= e(
                                            $item['product_name']
                                        ) ?>

                                    </div>


                                    <div class="item-meta">

                                        <?= (int)$item['quantity'] ?>

                                        ×

                                        ₹<?= number_format(
                                            (float)$item['unit_price'],
                                            2
                                        ) ?>

                                    </div>

                                </div>


                                <div class="item-total">

                                    ₹<?= number_format(
                                        (float)$item['line_total'],
                                        2
                                    ) ?>

                                </div>


                            </div>


                        </div>

                    <?php endforeach; ?>


                    <div class="order-total">

                        <span>
                            Total
                        </span>

                        <span>

                            ₹<?= number_format(
                                (float)$order['subtotal'],
                                2
                            ) ?>

                        </span>

                    </div>


                </div>

            </div>


            <!-- Status -->

            <div class="card">

                <div class="card-header">

                    <h3>
                        Order Status
                    </h3>

                </div>


                <div class="card-body">


                    <div>

                        Current status:

                        <span
                            class="status status-<?= e(
                                $order['status']
                            ) ?>"
                        >
                            <?= e(
                                statusLabel(
                                    $order['status']
                                )
                            ) ?>
                        </span>

                    </div>


                    <form
                        method="POST"
                        action="order-status.php"
                        class="status-form"
                    >

                        <?= csrf_field() ?>


                        <input
                            type="hidden"
                            name="order_id"
                            value="<?= (int)$order['id'] ?>"
                        >


                        <label for="status">
                            Change Status
                        </label>


                        <select
                            id="status"
                            name="status"
                        >

                            <?php foreach (
                                $allowedStatuses
                                as $status
                            ): ?>

                                <option
                                    value="<?= e($status) ?>"
                                    <?= $order['status'] === $status
                                        ? 'selected'
                                        : ''
                                    ?>
                                >

                                    <?= e(
                                        statusLabel(
                                            $status
                                        )
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>


                        <button
                            type="submit"
                            class="update-btn"
                        >
                            Update Status
                        </button>

                    </form>


                </div>

            </div>


        </div>


    </div>

</div>


</body>

</html>