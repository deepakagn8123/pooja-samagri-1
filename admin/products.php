<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$stmt = $pdo->query("
    SELECT
        p.id,
        p.name,
        p.slug,
        p.price,
        p.old_price,
        p.unit,
        p.image,
        p.is_active,
        c.name AS category_name
    FROM products p
    INNER JOIN categories c ON c.id = p.category_id
    ORDER BY p.id DESC
");

$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Products — Nitya Ritual E-Store</title>

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

.main {
    margin-left: 240px;
    min-height: 100vh;
}

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

.content {
    padding: 30px;
}

.page-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-head h1 {
    margin: 0 0 6px;
}

.page-head p {
    margin: 0;
    color: #666;
}

.btn {
    display: inline-block;
    background: #8B1E1E;
    color: white;
    padding: 11px 16px;
    border-radius: 6px;
    text-decoration: none;
    border: 0;
    cursor: pointer;
}

.table-box {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 14px 18px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #fafafa;
    font-size: 13px;
    white-space: nowrap;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 220px;
}

.product-image {
    width: 52px;
    height: 52px;
    object-fit: cover;
    border-radius: 7px;
    background: #eee;
}

.image-placeholder {
    width: 52px;
    height: 52px;
    border-radius: 7px;
    background: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #777;
    text-align: center;
}

.product-slug {
    color: #888;
    font-size: 12px;
    margin-top: 4px;
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

.actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 7px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    border: 1px solid #ddd;
    background: white;
    color: #333;
}

.action-btn:hover {
    background: #f5f5f5;
}

.price-old {
    display: block;
    color: #999;
    font-size: 12px;
    text-decoration: line-through;
}

.empty {
    padding: 40px;
    text-align: center;
    color: #777;
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

        <a href="products.php" class="active">
            Products
        </a>

        <a href="add-product.php">
            Add Product
        </a>

        <a href="categories.php">
            Categories
        </a>

        <a href="../index.php" target="_blank">
            View Website
        </a>

    </nav>

</div>


<div class="main">

    <div class="topbar">

        <h2>Products</h2>

        <div class="admin-info">

            <span>
                <?= e($_SESSION['admin_name'] ?? 'Admin') ?>
            </span>

            <a href="logout.php" class="logout">
                Logout
            </a>

        </div>

    </div>


    <div class="content">


        <div class="page-head">

            <div>

                <h1>Manage Products</h1>

                <p>
                    <?= count($products) ?> products found
                </p>

            </div>


            <a href="add-product.php" class="btn">
                + Add Product
            </a>

        </div>


        <div class="table-box">

            <?php if (!$products): ?>

                <div class="empty">
                    No products found.
                </div>

            <?php else: ?>


                <table>

                    <thead>

                        <tr>

                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php foreach ($products as $product): ?>

                        <tr>


                            <td>

                                <div class="product-info">


                                    <?php if (!empty($product['image'])): ?>

                                        <img
                                            src="../assets/images/products/<?= rawurlencode($product['image']) ?>"
                                            alt="<?= e($product['name']) ?>"
                                            class="product-image"
                                        >

                                    <?php else: ?>

                                        <div class="image-placeholder">
                                            No image
                                        </div>

                                    <?php endif; ?>


                                    <div>

                                        <strong>
                                            <?= e($product['name']) ?>
                                        </strong>

                                        <div class="product-slug">
                                            <?= e($product['slug']) ?>
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <td>
                                <?= e($product['category_name']) ?>
                            </td>


                            <td>

                                <?php if (!empty($product['old_price'])): ?>

                                    <span class="price-old">
                                        ₹<?= number_format((float)$product['old_price'], 0) ?>
                                    </span>

                                <?php endif; ?>

                                ₹<?= number_format((float)$product['price'], 0) ?>

                            </td>


                            <td>
                                <?= e($product['unit'] ?? '-') ?>
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


                            <td>

                                <div class="actions">

                                    <a
                                        href="../product.php?slug=<?= urlencode($product['slug']) ?>"
                                        class="action-btn"
                                        target="_blank"
                                    >
                                        View
                                    </a>


                                    <a
    href="edit-product.php?id=<?= (int)$product['id'] ?>"
    class="action-btn"
>
    Edit
</a>

<form
    method="POST"
    action="toggle-product.php"
    style="margin:0;"
>

    <input
        type="hidden"
        name="id"
        value="<?= (int)$product['id'] ?>"
    >

    <button
        type="submit"
        class="action-btn"
        style="cursor:pointer;"
    >
        <?= $product['is_active'] ? 'Disable' : 'Activate' ?>
    </button>

</form>

                                </div>

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