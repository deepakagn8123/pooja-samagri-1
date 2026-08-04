<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$categories = getAllCategories($pdo);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $price = trim($_POST['price'] ?? '');
    $oldPrice = trim($_POST['old_price'] ?? '');
    $unit = trim($_POST['unit'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $badge = trim($_POST['badge'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    /*
    |--------------------------------------------------------------------------
    | Basic validation
    |--------------------------------------------------------------------------
    */

    if (
        $name === '' ||
        $slug === '' ||
        $categoryId < 1 ||
        $price === ''
    ) {

        $error = 'Name, slug, category and price are required.';

    } elseif (!is_numeric($price) || (float)$price < 0) {

        $error = 'Please enter a valid price.';

    } elseif ($oldPrice !== '' && (!is_numeric($oldPrice) || (float)$oldPrice < 0)) {

        $error = 'Please enter a valid old price.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | Normalize slug
        |--------------------------------------------------------------------------
        */

        $slug = strtolower($slug);

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        $slug = trim($slug, '-');


        /*
        |--------------------------------------------------------------------------
        | Check duplicate slug
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT id
            FROM products
            WHERE slug = :slug
            LIMIT 1
        ");

        $stmt->execute([
            'slug' => $slug
        ]);

        if ($stmt->fetch()) {

            $error = 'A product with this slug already exists.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Image upload
            |--------------------------------------------------------------------------
            */

            $imageName = null;


            if (
                isset($_FILES['image']) &&
                $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
            ) {

                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

                    $error = 'Image upload failed.';

                } else {

                    $allowedMimeTypes = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/webp' => 'webp'
                    ];


                    $finfo = new finfo(FILEINFO_MIME_TYPE);

                    $mimeType = $finfo->file(
                        $_FILES['image']['tmp_name']
                    );


                    if (!isset($allowedMimeTypes[$mimeType])) {

                        $error = 'Only JPG, PNG and WEBP images are allowed.';

                    } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {

                        $error = 'Image must be smaller than 5 MB.';

                    } else {

                        $extension = $allowedMimeTypes[$mimeType];

                        $imageName =
                            $slug .
                            '-' .
                            bin2hex(random_bytes(4)) .
                            '.' .
                            $extension;


                        $uploadDirectory =
                            __DIR__ .
                            '/../assets/images/products/';


                        if (!is_dir($uploadDirectory)) {

                            mkdir(
                                $uploadDirectory,
                                0755,
                                true
                            );
                        }


                        $destination =
                            $uploadDirectory .
                            $imageName;


                        if (!move_uploaded_file(
                            $_FILES['image']['tmp_name'],
                            $destination
                        )) {

                            $error = 'Unable to save product image.';

                        }

                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Insert product
            |--------------------------------------------------------------------------
            */

            if ($error === '') {

                try {

                    $stmt = $pdo->prepare("
                        INSERT INTO products
                        (
                            category_id,
                            name,
                            slug,
                            price,
                            old_price,
                            unit,
                            description,
                            tag,
                            badge,
                            image,
                            is_active
                        )
                        VALUES
                        (
                            :category_id,
                            :name,
                            :slug,
                            :price,
                            :old_price,
                            :unit,
                            :description,
                            :tag,
                            :badge,
                            :image,
                            :is_active
                        )
                    ");


                    $stmt->execute([

                        'category_id' => $categoryId,

                        'name' => $name,

                        'slug' => $slug,

                        'price' => (float)$price,

                        'old_price' =>
                            $oldPrice !== ''
                                ? (float)$oldPrice
                                : null,

                        'unit' =>
                            $unit !== ''
                                ? $unit
                                : null,

                        'description' =>
                            $description !== ''
                                ? $description
                                : null,

                        'tag' =>
                            $tag !== ''
                                ? $tag
                                : null,

                        'badge' =>
                            $badge !== ''
                                ? $badge
                                : null,

                        'image' => $imageName,

                        'is_active' => $isActive
                    ]);


                    header(
                        'Location: products.php?added=1'
                    );

                    exit;


                } catch (PDOException $exception) {

                    /*
                     * If database insertion fails,
                     * remove the uploaded image.
                     */

                    if (
                        $imageName &&
                        isset($destination) &&
                        file_exists($destination)
                    ) {

                        unlink($destination);

                    }


                    $error = 'Unable to add product.';

                }

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

<title>Add Product — Nitya Ritual E-Store</title>

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

.form-box {
    max-width: 900px;
    background: white;
    padding: 25px;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full {
    grid-column: 1 / -1;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    font-size: 14px;
}

input,
select,
textarea {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font: inherit;
}

textarea {
    min-height: 130px;
    resize: vertical;
}

.checkbox-row {
    display: flex;
    align-items: center;
    gap: 9px;
}

.checkbox-row input {
    width: auto;
}

.checkbox-row label {
    margin: 0;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 5px;
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

.alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-error {
    background: #fde8e8;
    color: #a61b1b;
}

.help {
    margin-top: 5px;
    font-size: 12px;
    color: #777;
}

@media (max-width: 800px) {

    .form-grid {
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

        <a href="add-product.php" class="active">
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

        <h2>Add Product</h2>

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

            <h1>Add New Product</h1>

            <p>
                Add a new product to the store.
            </p>

        </div>


        <div class="form-box">


            <?php if ($error !== ''): ?>

                <div class="alert alert-error">
                    <?= e($error) ?>
                </div>

            <?php endif; ?>


            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <div class="form-grid">


                    <div class="form-group">

                        <label>
                            Product Name *
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="product-name"
                            value="<?= e($_POST['name'] ?? '') ?>"
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
                            id="product-slug"
                            value="<?= e($_POST['slug'] ?? '') ?>"
                            required
                        >

                        <div class="help">
                            Used in the product URL.
                        </div>

                    </div>


                    <div class="form-group">

                        <label>
                            Category *
                        </label>

                        <select
                            name="category_id"
                            required
                        >

                            <option value="">
                                Select category
                            </option>


                            <?php foreach ($categories as $category): ?>

                                <option
                                    value="<?= (int)$category['id'] ?>"
                                    <?= ((int)($_POST['category_id'] ?? 0) === (int)$category['id']) ? 'selected' : '' ?>
                                >
                                    <?= e($category['name']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Price *
                        </label>

                        <input
                            type="number"
                            name="price"
                            min="0"
                            step="0.01"
                            value="<?= e($_POST['price'] ?? '') ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Old Price
                        </label>

                        <input
                            type="number"
                            name="old_price"
                            min="0"
                            step="0.01"
                            value="<?= e($_POST['old_price'] ?? '') ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Unit
                        </label>

                        <input
                            type="text"
                            name="unit"
                            placeholder="/ piece"
                            value="<?= e($_POST['unit'] ?? '') ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Tag
                        </label>

                        <input
                            type="text"
                            name="tag"
                            placeholder="Popular"
                            value="<?= e($_POST['tag'] ?? '') ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Badge
                        </label>

                        <input
                            type="text"
                            name="badge"
                            placeholder="Best Seller"
                            value="<?= e($_POST['badge'] ?? '') ?>"
                        >

                    </div>


                    <div class="form-group full">

                        <label>
                            Description
                        </label>

                        <textarea
                            name="description"
                        ><?= e($_POST['description'] ?? '') ?></textarea>

                    </div>


                    <div class="form-group full">

                        <label>
                            Product Image
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <div class="help">
                            JPG, PNG or WEBP. Maximum 5 MB.
                        </div>

                    </div>


                    <div class="form-group full">

                        <div class="checkbox-row">

                            <input
                                type="checkbox"
                                name="is_active"
                                id="is-active"
                                value="1"
                                <?= !isset($_POST['name']) || isset($_POST['is_active']) ? 'checked' : '' ?>
                            >

                            <label for="is-active">
                                Active product
                            </label>

                        </div>

                    </div>


                </div>


                <div class="actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Add Product
                    </button>

                    <a
                        href="products.php"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </div>

    </div>

</div>


<script>

const nameInput = document.getElementById("product-name");
const slugInput = document.getElementById("product-slug");

let slugManuallyEdited = false;


slugInput.addEventListener("input", function () {

    slugManuallyEdited = true;

});


nameInput.addEventListener("input", function () {

    if (slugManuallyEdited) {
        return;
    }

    slugInput.value = nameInput.value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");

});

</script>


</body>
</html>