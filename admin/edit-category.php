<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$categoryId = (int)($_GET['id'] ?? 0);

if ($categoryId < 1) {
    header('Location: categories.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, name, slug
    FROM categories
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    'id' => $categoryId
]);

$category = $stmt->fetch();

if (!$category) {
    header('Location: categories.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if ($name === '') {

        $error = 'Category name is required.';

    } else {

        if ($slug === '') {
            $slug = $name;
        }

        $slug = strtolower($slug);

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        $slug = trim($slug, '-');

        if ($slug === '') {

            $error = 'Please enter a valid slug.';

        } else {

            $stmt = $pdo->prepare("
                SELECT id
                FROM categories
                WHERE slug = :slug
                  AND id != :id
                LIMIT 1
            ");

            $stmt->execute([
                'slug' => $slug,
                'id' => $categoryId
            ]);

            if ($stmt->fetch()) {

                $error = 'Another category already uses this slug.';

            } else {

                $stmt = $pdo->prepare("
                    UPDATE categories
                    SET
                        name = :name,
                        slug = :slug
                    WHERE id = :id
                ");

                $stmt->execute([
                    'name' => $name,
                    'slug' => $slug,
                    'id' => $categoryId
                ]);

                header(
                    'Location: categories.php?updated=1'
                );

                exit;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Category — Nitya Ritual E-Store</title>

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

.form-box {
    max-width: 600px;
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font: inherit;
}

.help {
    color: #777;
    font-size: 12px;
    margin-top: 5px;
}

.alert {
    padding: 12px 15px;
    background: #fde8e8;
    color: #a61b1b;
    border-radius: 6px;
    margin-bottom: 20px;
}

.actions {
    display: flex;
    gap: 10px;
}

.btn {
    border: 0;
    border-radius: 6px;
    padding: 11px 18px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
}

.btn-primary {
    background: #8B1E1E;
    color: white;
}

.btn-secondary {
    background: #eee;
    color: #333;
}

</style>

</head>

<body>

<div class="sidebar">

    <div class="sidebar-brand">
        Nitya Ritual E-Store
    </div>

    <nav>

        <a href="index.php">Dashboard</a>

        <a href="products.php">Products</a>

        <a href="add-product.php">Add Product</a>

        <a href="categories.php" class="active">
            Categories
        </a>

        <a href="../index.php" target="_blank">
            View Website
        </a>

    </nav>

</div>


<div class="main">

    <div class="topbar">

        <h2>Edit Category</h2>

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

        <h1>Edit Category</h1>

        <div class="form-box">


            <?php if ($error !== ''): ?>

                <div class="alert">
                    <?= e($error) ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="form-group">

                    <label>
                        Category Name *
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="<?= e($_POST['name'] ?? $category['name']) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Slug *
                    </label>

                    <input
                        type="text"
                        name="slug"
                        value="<?= e($_POST['slug'] ?? $category['slug']) ?>"
                        required
                    >

                    <div class="help">
                        Changing this may affect category URLs.
                    </div>

                </div>


                <div class="actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Changes
                    </button>

                    <a
                        href="categories.php"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </div>

    </div>

</div>

</body>
</html>