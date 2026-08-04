<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$error = '';

/*
|--------------------------------------------------------------------------
| Add Category
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if ($name === '') {

        $error = 'Category name is required.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | Generate / Normalize Slug
        |--------------------------------------------------------------------------
        */

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

            $error = 'Please enter a valid category name or slug.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Duplicate Check
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                SELECT id
                FROM categories
                WHERE slug = :slug
                LIMIT 1
            ");

            $stmt->execute([
                'slug' => $slug
            ]);

            if ($stmt->fetch()) {

                $error = 'A category with this slug already exists.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | Insert
                |--------------------------------------------------------------------------
                */

                $stmt = $pdo->prepare("
                    INSERT INTO categories
                    (
                        name,
                        slug
                    )
                    VALUES
                    (
                        :name,
                        :slug
                    )
                ");

                $stmt->execute([
                    'name' => $name,
                    'slug' => $slug
                ]);

                header(
                    'Location: categories.php?added=1'
                );

                exit;
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| Categories + Product Count
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT
        c.id,
        c.name,
        c.slug,
        COUNT(p.id) AS product_count
    FROM categories c
    LEFT JOIN products p
        ON p.category_id = c.id
    GROUP BY
        c.id,
        c.name,
        c.slug
    ORDER BY c.name ASC
");

$categories = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Categories — Nitya Ritual E-Store</title>

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
    margin-bottom: 25px;
}

.page-head h1 {
    margin: 0 0 7px;
}

.page-head p {
    margin: 0;
    color: #666;
}

.grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 25px;
    align-items: start;
}

.box {
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    overflow: hidden;
}

.box-head {
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
}

.box-head h3 {
    margin: 0;
}

.box-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    font-size: 14px;
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

.btn {
    border: 0;
    border-radius: 6px;
    padding: 11px 16px;
    background: #8B1E1E;
    color: white;
    cursor: pointer;
    font-size: 14px;
}

.alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 18px;
}

.alert-error {
    background: #fde8e8;
    color: #a61b1b;
}

.alert-success {
    background: #e4f7e8;
    color: #1d7a35;
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
}

.slug {
    color: #777;
    font-size: 13px;
}

.count {
    display: inline-block;
    background: #f1f1f1;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 12px;
}

@media (max-width: 900px) {

    .grid {
        grid-template-columns: 1fr;
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

        <a href="add-product.php">
            Add Product
        </a>

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

        <h2>Categories</h2>

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

            <h1>Manage Categories</h1>

            <p>
                Create and manage product categories.
            </p>

        </div>


        <?php if (isset($_GET['added'])): ?>

            <div class="alert alert-success">
                Category added successfully.
            </div>

        <?php endif; ?>


        <?php if (isset($_GET['updated'])): ?>

            <div class="alert alert-success">
                Category updated successfully.
            </div>

        <?php endif; ?>


        <?php if (isset($_GET['deleted'])): ?>

            <div class="alert alert-success">
                Category deleted successfully.
            </div>

        <?php endif; ?>


        <?php if (($_GET['delete'] ?? '') === 'blocked'): ?>

            <div class="alert alert-error">
                This category cannot be deleted because products are assigned to it.
            </div>

        <?php endif; ?>


        <div class="grid">


            <!-- ADD CATEGORY -->

            <div class="box">

                <div class="box-head">
                    <h3>Add Category</h3>
                </div>

                <div class="box-body">


                    <?php if ($error !== ''): ?>

                        <div class="alert alert-error">
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
                                id="category-name"
                                value="<?= e($_POST['name'] ?? '') ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                id="category-slug"
                                value="<?= e($_POST['slug'] ?? '') ?>"
                            >

                            <div class="help">
                                Leave empty to generate automatically.
                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn"
                        >
                            Add Category
                        </button>


                    </form>

                </div>

            </div>


            <!-- CATEGORY LIST -->

            <div class="box">

                <div class="box-head">

                    <h3>
                        Categories (<?= count($categories) ?>)
                    </h3>

                </div>


                <?php if (!$categories): ?>

                    <div class="box-body">
                        No categories found.
                    </div>

                <?php else: ?>


                    <table>

                        <thead>

                            <tr>
                                <th>Category</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>

                        </thead>


                        <tbody>


                        <?php foreach ($categories as $category): ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?= e($category['name']) ?>
                                    </strong>
                                </td>

                                <td class="slug">
                                    <?= e($category['slug']) ?>
                                </td>

                                <td>

                                    <span class="count">
                                        <?= (int)$category['product_count'] ?>
                                    </span>

                                </td>

                                <td>

                                    <div style="display:flex;gap:8px;align-items:center;">

                                        <a
                                            href="edit-category.php?id=<?= (int)$category['id'] ?>"
                                            style="
                                                padding:7px 10px;
                                                border:1px solid #ddd;
                                                border-radius:5px;
                                                text-decoration:none;
                                                color:#333;
                                                font-size:12px;
                                            "
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="delete-category.php"
                                            style="margin:0;"
                                            onsubmit="return confirm('Delete this category?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$category['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                <?= ((int)$category['product_count'] > 0) ? 'disabled' : '' ?>
                                                style="
                                                    padding:7px 10px;
                                                    border:1px solid #ddd;
                                                    border-radius:5px;
                                                    font-size:12px;
                                                    cursor:<?= ((int)$category['product_count'] > 0) ? 'not-allowed' : 'pointer' ?>;
                                                    opacity:<?= ((int)$category['product_count'] > 0) ? '0.5' : '1' ?>;
                                                "
                                            >
                                                Delete
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

</div>


<script>

const nameInput =
    document.getElementById("category-name");

const slugInput =
    document.getElementById("category-slug");


let slugManuallyEdited = false;


slugInput.addEventListener(
    "input",
    function () {

        slugManuallyEdited =
            slugInput.value.trim() !== "";

    }
);


nameInput.addEventListener(
    "input",
    function () {

        if (slugManuallyEdited) {
            return;
        }

        slugInput.value =
            nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-+|-+$/g, "");

    }
);

</script>


</body>

</html>