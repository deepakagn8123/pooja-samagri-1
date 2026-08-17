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


/*
|--------------------------------------------------------------------------
| Recent Products
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT
        p.id,
        p.name,
        p.slug,
        p.price,
        p.image,
        p.is_active,
        c.name AS category_name
    FROM products p
    INNER JOIN categories c ON c.id = p.category_id
    ORDER BY p.id DESC
    LIMIT 5
");

$recentProducts = $stmt->fetchAll();


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


/* Responsive */

@media (max-width: 900px) {

    .stats {
        grid-template-columns: repeat(2, 1fr);
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

        <a href="index.php" class="active">
            Dashboard
        </a>

        <a href="products.php">
            Products
        </a>

        <a href="#">
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


<div class="main">


    <div class="topbar">

        <h2>Dashboard</h2>

        <div class="admin-info">

            <span>
                <?= e($_SESSION['admin_name'] ?? 'Admin') ?>
            </span>

            <form method="POST" action="logout.php">

    <?= csrf_field() ?>

    <button type="submit">
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


        <div class="section">


            <div class="section-header">

                <h3>Recent Products</h3>

                <a href="products.php" class="btn">
                    Manage Products
                </a>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>


                <?php if (!$recentProducts): ?>

                    <tr>

                        <td colspan="4">
                            No products found.
                        </td>

                    </tr>


                <?php else: ?>


                    <?php foreach ($recentProducts as $product): ?>

                        <tr>


                            <td>

                                <div class="product-info">

                                    <?php if (!empty($product['image'])): ?>

                                        <img
                                            src="../assets/images/products/<?= rawurlencode($product['image']) ?>"
                                            class="product-image"
                                            alt="<?= e($product['name']) ?>"
                                        >

                                    <?php endif; ?>


                                    <div>

                                        <strong>
                                            <?= e($product['name']) ?>
                                        </strong>

                                    </div>

                                </div>

                            </td>


                            <td>
                                <?= e($product['category_name']) ?>
                            </td>


                            <td>

                                ₹<?= number_format((float)$product['price'], 0) ?>

                            </td>


                            <td>

                                <?php if ($product['is_active']): ?>

                                    <span class="status active">
                                        Active
                                    </span>

                                <?php else: ?>

                                    <span class="status inactive">
                                        Inactive
                                    </span>

                                <?php endif; ?>

                            </td>


                        </tr>

                    <?php endforeach; ?>


                <?php endif; ?>


                </tbody>

            </table>


        </div>


    </div>

</div>


</body>

</html>