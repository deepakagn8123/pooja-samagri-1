<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$productId = (int)($_GET['id'] ?? 0);

if ($productId < 1) {
    header('Location: products.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Product
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    'id' => $productId
]);

$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}


$categories = getAllCategories($pdo);

$error = '';


/*
|--------------------------------------------------------------------------
| Update Product
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

verify_csrf();

$name = request_string($_POST['name'] ?? null);
$slug = request_string($_POST['slug'] ?? null);
$categoryId = filter_var(
    $_POST['category_id'] ?? null,
    FILTER_VALIDATE_INT
);

$categoryId = $categoryId !== false && $categoryId !== null
    ? $categoryId
    : 0;

$price = request_string($_POST['price'] ?? null);
$oldPrice = request_string($_POST['old_price'] ?? null);

$unit = request_string($_POST['unit'] ?? null);
$description = request_string($_POST['description'] ?? null);
$tag = request_string($_POST['tag'] ?? null);
$badge = request_string($_POST['badge'] ?? null);

    $isActive = isset($_POST['is_active']) ? 1 : 0;


    /*
    |--------------------------------------------------------------------------
    | Validation
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

    } elseif (
        $oldPrice !== '' &&
        (!is_numeric($oldPrice) || (float)$oldPrice < 0)
    ) {

        $error = 'Please enter a valid old price.';

    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Slug
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        $slug = strtolower($slug);

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        $slug = trim($slug, '-');

        if (
    $slug === '' ||
    !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)
) {
    $error = 'Please enter a valid product slug.';
}


        if ($slug === '') {

            $error = 'Please enter a valid slug.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Check Duplicate Slug
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        $stmt = $pdo->prepare("
            SELECT id
            FROM products
            WHERE slug = :slug
              AND id != :id
            LIMIT 1
        ");

        $stmt->execute([
            'slug' => $slug,
            'id' => $productId
        ]);

        if ($stmt->fetch()) {

            $error = 'Another product already uses this slug.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Image
    |--------------------------------------------------------------------------
    */

    $imageName = $product['image'];

    $newImageUploaded = false;

    $newImagePath = null;


    if (
        $error === '' &&
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

                $newImageName =
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


                $newImagePath =
                    $uploadDirectory .
                    $newImageName;


                if (
                    move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        $newImagePath
                    )
                ) {

                    $imageName = $newImageName;

                    $newImageUploaded = true;

                } else {

                    $error = 'Unable to save product image.';

                }

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Update Database
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        try {

            $stmt = $pdo->prepare("
                UPDATE products
                SET
                    category_id = :category_id,
                    name = :name,
                    slug = :slug,
                    price = :price,
                    old_price = :old_price,
                    unit = :unit,
                    description = :description,
                    tag = :tag,
                    badge = :badge,
                    image = :image,
                    is_active = :is_active
                WHERE id = :id
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

                'is_active' => $isActive,

                'id' => $productId
            ]);


            /*
            |--------------------------------------------------------------------------
            | Remove Old Image After Successful Update
            |--------------------------------------------------------------------------
            */

            if (
                $newImageUploaded &&
                !empty($product['image']) &&
                $product['image'] !== $imageName
            ) {

                $oldImagePath =
                    __DIR__ .
                    '/../assets/images/products/' .
                    $product['image'];


                if (is_file($oldImagePath)) {

                    unlink($oldImagePath);

                }

            }


            header(
                'Location: products.php?updated=1'
            );

            exit;


        } catch (PDOException $exception) {

            /*
             * Database failed, so remove newly uploaded image.
             */

            if (
                $newImageUploaded &&
                $newImagePath &&
                is_file($newImagePath)
            ) {

                unlink($newImagePath);

            }


            $error = 'Unable to update product.';

        }

    }

}


/*
|--------------------------------------------------------------------------
| Values Displayed In Form
|--------------------------------------------------------------------------
*/

$form = $product;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = array_merge(
        $product,
        [
            'name' => $_POST['name'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'price' => $_POST['price'] ?? '',
            'old_price' => $_POST['old_price'] ?? '',
            'unit' => $_POST['unit'] ?? '',
            'description' => $_POST['description'] ?? '',
            'tag' => $_POST['tag'] ?? '',
            'badge' => $_POST['badge'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]
    );

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Product — Nitya Ritual E-Store</title>

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

.current-image {
    margin-bottom: 12px;
}

.current-image img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.help {
    margin-top: 5px;
    color: #777;
    font-size: 12px;
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

    <h2>Edit Product</h2>

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


<div class="page-head">

    <h1>Edit Product</h1>

    <p>
        <?= e($product['name']) ?>
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

<?= csrf_field() ?>


<div class="form-grid">


<div class="form-group">

    <label>Product Name *</label>

    <input
        type="text"
        name="name"
        value="<?= e($form['name']) ?>"
        required
    >

</div>


<div class="form-group">

    <label>Slug *</label>

    <input
        type="text"
        name="slug"
        value="<?= e($form['slug']) ?>"
        required
    >

</div>


<div class="form-group">

    <label>Category *</label>

    <select
        name="category_id"
        required
    >

        <?php foreach ($categories as $category): ?>

            <option
                value="<?= (int)$category['id'] ?>"
                <?= ((int)$form['category_id'] === (int)$category['id']) ? 'selected' : '' ?>
            >
                <?= e($category['name']) ?>
            </option>

        <?php endforeach; ?>

    </select>

</div>


<div class="form-group">

    <label>Price *</label>

    <input
        type="number"
        name="price"
        min="0"
        step="0.01"
        value="<?= e($form['price']) ?>"
        required
    >

</div>


<div class="form-group">

    <label>Old Price</label>

    <input
        type="number"
        name="old_price"
        min="0"
        step="0.01"
        value="<?= e($form['old_price'] ?? '') ?>"
    >

</div>


<div class="form-group">

    <label>Unit</label>

    <input
        type="text"
        name="unit"
        value="<?= e($form['unit'] ?? '') ?>"
    >

</div>


<div class="form-group">

    <label>Tag</label>

    <input
        type="text"
        name="tag"
        value="<?= e($form['tag'] ?? '') ?>"
    >

</div>


<div class="form-group">

    <label>Badge</label>

    <input
        type="text"
        name="badge"
        value="<?= e($form['badge'] ?? '') ?>"
    >

</div>


<div class="form-group full">

    <label>Description</label>

    <textarea name="description"><?= e($form['description'] ?? '') ?></textarea>

</div>


<div class="form-group full">

    <label>Product Image</label>


    <?php if (!empty($product['image'])): ?>

        <div class="current-image">

            <img
                src="../assets/images/products/<?= rawurlencode($product['image']) ?>"
                alt="<?= e($product['name']) ?>"
            >

        </div>

    <?php endif; ?>


    <input
        type="file"
        name="image"
        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
    >

    <div class="help">
        Leave empty to keep the current image.
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
            <?= !empty($form['is_active']) ? 'checked' : '' ?>
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
        Save Changes
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


</body>
</html>