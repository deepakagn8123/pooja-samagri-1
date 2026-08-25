<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();


/*
|--------------------------------------------------------------------------
| Status filter
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

$statusFilter = $_GET['status'] ?? '';

if (
    $statusFilter !== '' &&
    !in_array($statusFilter, $allowedStatuses, true)
) {
    $statusFilter = '';
}


/*
|--------------------------------------------------------------------------
| Orders
|--------------------------------------------------------------------------
*/

if ($statusFilter !== '') {

    $stmt = $pdo->prepare("
        SELECT
            id,
            order_number,
            customer_name,
            customer_phone,
            subtotal,
            status,
            created_at
        FROM orders
        WHERE status = ?
        ORDER BY id DESC
    ");

    $stmt->execute([
        $statusFilter
    ]);

} else {

    $stmt = $pdo->query("
        SELECT
            id,
            order_number,
            customer_name,
            customer_phone,
            subtotal,
            status,
            created_at
        FROM orders
        ORDER BY id DESC
    ");
}


$orders = $stmt->fetchAll();


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

<title>Orders — Nitya Ritual E-Store</title>


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


.logout {
    color: #8B1E1E;
    text-decoration: none;
    font-weight: bold;
}


/* Content */

.content {
    padding: 30px;
}


.page-heading {
    margin-bottom: 25px;
}


.page-heading h1 {
    margin: 0 0 8px;
}


.page-heading p {
    margin: 0;
    color: #666;
}


/* Filters */

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}


.filter {
    padding: 9px 14px;
    border-radius: 20px;
    background: white;
    border: 1px solid #ddd;
    color: #444;
    text-decoration: none;
    font-size: 13px;
}


.filter:hover,
.filter.active {
    background: #8B1E1E;
    color: white;
    border-color: #8B1E1E;
}


/* Section */

.section {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    overflow: hidden;
}


/* Table */

table {
    width: 100%;
    border-collapse: collapse;
}


th,
td {
    text-align: left;
    padding: 14px 18px;
    border-bottom: 1px solid #eee;
}


th {
    background: #fafafa;
    font-size: 13px;
}


.order-number {
    font-weight: bold;
}


.customer-name {
    font-weight: 600;
}


.customer-phone {
    color: #777;
    font-size: 13px;
    margin-top: 3px;
}


/* Status */

.status {
    display: inline-block;
    padding: 6px 10px;
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


/* View button */

.view-btn {
    display: inline-block;
    padding: 8px 12px;
    background: #8B1E1E;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}


.view-btn:hover {
    opacity: .9;
}


/* Empty */

.empty {
    padding: 40px;
    text-align: center;
    color: #777;
}


/* Responsive */

@media (max-width: 900px) {

    .sidebar {
        width: 200px;
    }

    .main {
        margin-left: 200px;
    }

    .content {
        padding: 20px;
    }

    table {
        min-width: 850px;
    }

    .section {
        overflow-x: auto;
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
            Orders
        </h2>


        <div class="admin-info">

            <span>
                <?= e($_SESSION['admin_name'] ?? 'Admin') ?>
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


        <div class="page-heading">

            <h1>
                Orders
            </h1>

            <p>
                Manage customer orders and their status.
            </p>

        </div>


        <div class="filters">

            <!-- <a
                href="orders.php"
                class="filter <?= $statusFilter === '' ? 'active' : '' ?>"
            >
                All
            </a> -->


            <?php foreach ($allowedStatuses as $status): ?>

                <a
                    href="orders.php?status=<?= urlencode($status) ?>"
                    class="filter <?= $statusFilter === $status ? 'active' : '' ?>"
                >
                    <?= e(statusLabel($status)) ?>
                </a>

            <?php endforeach; ?>

        </div>


        <div class="section">


            <?php if (!$orders): ?>

                <div class="empty">
                    No orders found.
                </div>


            <?php else: ?>

                <table>

                    <thead>

                        <tr>

                            <th>
                                Order
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($orders as $order): ?>

                        <tr>

                            <td>

                                <div class="order-number">
                                    <?= e($order['order_number']) ?>
                                </div>

                            </td>


                            <td>

                                <div class="customer-name">
                                    <?= e($order['customer_name']) ?>
                                </div>

                                <div class="customer-phone">
                                    <?= e($order['customer_phone']) ?>
                                </div>

                            </td>


                            <td>

                                ₹<?= number_format(
                                    (float)$order['subtotal'],
                                    2
                                ) ?>

                            </td>


                            <td>

                                <?= e(
                                    date(
                                        'd M Y, h:i A',
                                        strtotime(
                                            $order['created_at']
                                        )
                                    )
                                ) ?>

                            </td>


                            <td>

                                <span
                                    class="status status-<?= e($order['status']) ?>"
                                >
                                    <?= e(
                                        statusLabel(
                                            $order['status']
                                        )
                                    ) ?>
                                </span>

                            </td>


                            <td>

                                <a
                                    href="order-view.php?id=<?= (int)$order['id'] ?>"
                                    class="view-btn"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>


        </div>


    </div>

</div>


</body>

</html>