<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$totalProducts = (int) $pdo->query("
    SELECT COUNT(*)
    FROM products
")->fetchColumn();

$activeProducts = (int) $pdo->query("
    SELECT COUNT(*)
    FROM products
    WHERE is_active = 1
")->fetchColumn();

$inactiveProducts = (int) $pdo->query("
    SELECT COUNT(*)
    FROM products
    WHERE is_active = 0
")->fetchColumn();

$totalCategories = (int) $pdo->query("
    SELECT COUNT(*)
    FROM categories
")->fetchColumn();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard — Nitya Ritual E-Store</title>

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

.welcome {
    margin-bottom: 25px;
}

.welcome h1 {
    margin: 0 0 8px;
}

.welcome p {
    margin: 0;
    color: #666;
}


/* Stats */

.stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 22px;
    border-radius: 10px;
    border: 1px solid #e5e5e5;
}

.stat-card span {
    color: #777;
    font-size: 14px;
}

.stat-card h2 {
    margin: 10px 0 0;
    font-size: 30px;
    color: #8B1E1E;
}


/* Section */

.section {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    overflow: hidden;
}

.section-header {
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h3 {
    margin: 0;
}

.btn {
    background: #8B1E1E;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}


/* Table */

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    text-align: left;
    padding: 14px 20px;
    border-bottom: 1px solid #eee;
}

th {
    background: #fafafa;
    font-size: 13px;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-image {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 6px;
    background: #eee;
}

.status {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 12px;
}

.status.active {
    background: #e4f7e8;
    color: #1d7a35;
}

.status.inactive {
    background: #fde8e8;
    color: #b42318;
}

/* =========================================
   Mobile Dashboard Products
========================================= */

.mobile-price {
    display: none;
}

@media (max-width: 700px) {

.quick-link {
    padding: 15px;
    gap: 12px;
}

.quick-link-info {
    min-width: 0;
}

.quick-link-info strong {
    font-size: 14px;
}

.quick-link-info span {
    font-size: 11px;
    line-height: 1.4;
}

.quick-link .btn {
    padding: 9px 11px;
    font-size: 11px;
}

    /* Never allow horizontal scrolling */

    .section {
        overflow: hidden;
    }

    table {
        width: 100%;
        min-width: 0;
        table-layout: fixed;
    }

    /* Hide category on mobile */

    .category-column,
    th.category-column {
        display: none;
    }

    /* Hide desktop price column */

    .price-column,
    th.price-column {
        display: none;
    }

    /* Product column */



    /* Status column */


    .status-column {
        width: 30%;
        text-align: right;
    }

    th,
    td {
        padding: 13px 12px;
        font-size: 13px;
    }

    /* Product layout */

    .product-info {
        gap: 10px;
        min-width: 0;
    }

    .product-image {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }

    .product-details {
        min-width: 0;
    }

    .product-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-name strong {
        font-size: 13px;
    }

    /* Price shown below product name */

    .mobile-price {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: #777;
        font-weight: 600;
    }

    /* Status */

    .status-column {
        text-align: right;
        vertical-align: middle;
    }

    .status {
        padding: 5px 8px;
        font-size: 11px;
        white-space: nowrap;
    }

}

/* =========================================
   Mobile Navigation
========================================= */

.menu-toggle {
    display: none;
    border: none;
    background: transparent;
    font-size: 26px;
    cursor: pointer;
    color: #8B1E1E;
    padding: 0;
}

.sidebar-overlay {
    display: none;
}


/* =========================================
   Tablet
========================================= */

@media (max-width: 900px) {

    .stats {
        grid-template-columns: repeat(2, 1fr);
    }

}


/* =========================================
   Mobile
========================================= */

@media (max-width: 700px) {

    /* Sidebar */

    .sidebar {
        width: 260px;
        transform: translateX(-100%);
        transition: transform 0.25s ease;
        z-index: 1000;
    }

    .sidebar.open {
        transform: translateX(0);
    }

    /* Overlay */

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 999;
    }

    .sidebar.open + .sidebar-overlay {
        display: block;
    }

    /* Main */

    .main {
        margin-left: 0;
        width: 100%;
    }

    /* Topbar */

    .topbar {
        padding: 15px 18px;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 900;
    }

    .menu-toggle {
        display: block;
        flex-shrink: 0;
    }

    .topbar h2 {
        font-size: 20px;
        flex: 1;
    }

    .admin-info {
        gap: 10px;
    }

    .admin-info span {
        display: none;
    }

    .admin-info form {
        margin: 0;
    }

    .admin-info button {
        padding: 7px 11px;
        font-size: 13px;
    }

    /* Content */

    .content {
        padding: 20px 15px;
    }

    .welcome {
        margin-bottom: 20px;
    }

    .welcome h1 {
        font-size: 24px;
        line-height: 1.3;
    }

    .welcome p {
        font-size: 14px;
        line-height: 1.5;
    }

    /* Stats */

    .stats {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        padding: 16px;
        border-radius: 8px;
    }

    .stat-card span {
        font-size: 12px;
        line-height: 1.4;
    }

    .stat-card h2 {
        font-size: 25px;
        margin-top: 7px;
    }

    /* Section */

    .section {
        border-radius: 8px;
    }

    .section-header {
        padding: 15px;
        gap: 10px;
    }

    .section-header h3 {
        font-size: 16px;
    }

    .btn {
        padding: 8px 10px;
        font-size: 12px;
        white-space: nowrap;
    }

    /* Table becomes horizontally scrollable */

    .section {
        overflow-x: auto;
    }

    table {
        min-width: 650px;
    }

    th,
    td {
        padding: 12px 14px;
        font-size: 13px;
    }

    .product-image {
        width: 42px;
        height: 42px;
    }

}


/* =========================================
   Very Small Phones
========================================= */

@media (max-width: 400px) {

    .content {
        padding: 16px 12px;
    }

    .stats {
        gap: 10px;
    }

    .stat-card {
        padding: 14px 12px;
    }

    .stat-card h2 {
        font-size: 22px;
    }

    .section-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .section-header .btn {
        width: 100%;
        text-align: center;
    }

}

@media (max-width: 700px) {

    .section {
        overflow: hidden;
    }

    table {
        width: 100%;
        min-width: 0;
        table-layout: auto;
    }

    /* Hide Category and desktop Price */

    .category-column,
    .price-column,
    th.category-column,
    th.price-column {
        display: none;
    }

    /* Remove unnecessary table spacing */

    th,
    td {
        padding: 12px 10px;
    }

    /* Product column */

    th:first-child,
    td:first-child {
        width: auto;
    }

    /* Status column */

    th.status-column,
    td.status-column {
        width: 1%;
        white-space: nowrap;
        text-align: right;
        padding-left: 5px;
    }

    /* Product */

    .product-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .product-image {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
    }

    .product-details {
        min-width: 0;
        overflow: hidden;
    }

    .product-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-name strong {
        font-size: 13px;
    }

    /* Mobile price */

    .mobile-price {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: #777;
        font-weight: 600;
    }

    /* Status */

    .status {
        display: inline-block;
        padding: 5px 8px;
        font-size: 11px;
        white-space: nowrap;
    }

}


/* Responsive */

@media (max-width: 900px) {

    .stats {
        grid-template-columns: repeat(2, 1fr);
    }

}


.logout-btn {
    border: 1px solid #e5bcbc;
    background: #fff5f5;
    color: #8B1E1E;

    padding: 8px 14px;

    border-radius: 7px;

    font-size: 13px;
    font-weight: 600;

    cursor: pointer;

    transition:
        background 0.2s ease,
        color 0.2s ease,
        border-color 0.2s ease,
        transform 0.15s ease;
}

.logout-btn:hover {
    background: #8B1E1E;
    color: #fff;
    border-color: #8B1E1E;
}

.logout-btn:active {
    transform: scale(0.97);
}

/* =========================================
   Quick Links
========================================= */

.quick-link-list {
    width: 100%;
}

.quick-link {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 18px 20px;

    border-bottom: 1px solid #eee;

    gap: 20px;
}

.quick-link:last-child {
    border-bottom: none;
}

.quick-link-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.quick-link-info strong {
    font-size: 15px;
}

.quick-link-info span {
    color: #777;
    font-size: 13px;
}

.quick-link .btn {
    flex-shrink: 0;
}
</style>

</head>

<body>


<div class="sidebar">

    <div class="sidebar-brand">
        Nitya Ritual E-Store
    </div>

    <nav>

        <a href="index.php" class="active">
            Dashboard
        </a>

        <!-- <a href="orders.php">
    Orders
</a> -->

        <a href="products.php">
            Products
        </a>
        

        <a href="add-product.php">
            Add Product
        </a>

        <a href="categories.php">
            Categories
        </a>

        <a href="settings.php">Security Settings</a>

        <a href="../index.php" target="_blank">
            View Website
        </a>

    </nav>

</div>

<div class="sidebar-overlay" onclick="toggleSidebar()"></div>


<div class="main">


    <div class="topbar">

    <button class="menu-toggle" type="button" onclick="toggleSidebar()">
    ☰
</button>

        <h2>Dashboard</h2>

        <div class="admin-info">

            <span>
                <?= e($_SESSION['admin_name'] ?? 'Admin') ?>
            </span>

            <form method="POST" action="logout.php">

    <?= csrf_field() ?>

<button
    type="submit"
    class="logout-btn"
>
    Logout
</button>

</form>

        </div>

    </div>


    <div class="content">


        <div class="welcome">

            <h1>
                Welcome, <?= e($_SESSION['admin_name'] ?? 'Admin') ?>
            </h1>

            <p>
                Manage your Nitya Ritual E-Store from here.
            </p>

        </div>


        <div class="stats">


            <div class="stat-card">

                <span>Total Products</span>

                <h2>
                    <?= $totalProducts ?>
                </h2>

            </div>


            <div class="stat-card">

                <span>Active Products</span>

                <h2>
                    <?= $activeProducts ?>
                </h2>

            </div>


            <div class="stat-card">

                <span>Inactive Products</span>

                <h2>
                    <?= $inactiveProducts ?>
                </h2>

            </div>


            <div class="stat-card">

                <span>Categories</span>

                <h2>
                    <?= $totalCategories ?>
                </h2>

            </div>


        </div>


<div class="section quick-links">

    <div class="section-header">

        <h3>Quick Links</h3>

    </div>


    <div class="quick-link-list">

        <div class="quick-link">

            <div class="quick-link-info">

                <strong>Add Product</strong>

                <span>
                    Create a new product for your store.
                </span>

            </div>

            <a
                href="add-product.php"
                class="btn"
            >
                Add Product
            </a>

        </div>


        <div class="quick-link">

            <div class="quick-link-info">

                <strong>Add Category</strong>

                <span>
                    Create a new product category.
                </span>

            </div>

            <a
                href="categories.php"
                class="btn"
            >
                Add Category
            </a>

        </div>

    </div>

</div>

<script>

function toggleSidebar() {

    const sidebar = document.querySelector('.sidebar');

    sidebar.classList.toggle('open');

}

</script>
</body>

</html>