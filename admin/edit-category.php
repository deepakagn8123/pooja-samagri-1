<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$categoryId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($categoryId < 1) {
    header('Location: categories.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM categories
    WHERE id = :id
    LIMIT 1
");

$stmt->execute(['id' => $categoryId]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header('Location: categories.php');
    exit;
}

$error = '';

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    $description = trim($_POST['description'] ?? '');

    $sortOrder = max(
        0,
        (int)($_POST['sort_order'] ?? 0)
    );

    $isActive = isset($_POST['is_active'])
        ? 1
        : 0;

    $showOnHomepage = isset($_POST['show_on_homepage'])
        ? 1
        : 0;


    if ($name === '') {

        $error = 'Category name is required.';

    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Slug
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

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

            $error =
                'Please enter a valid category name or slug.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Duplicate Slug
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

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

            $error =
                'A category with this slug already exists.';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Image upload
    |--------------------------------------------------------------------------
    */

    $newImageName = $category['image'];

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if (
            $_FILES['image']['error']
            !== UPLOAD_ERR_OK
        ) {

            $error =
                'Category image upload failed.';

        } elseif (
            (int)$_FILES['image']['size']
            > 5 * 1024 * 1024
        ) {

            $error =
                'Category image must be smaller than 5 MB.';

        } else {

            $info = @getimagesize(
                $_FILES['image']['tmp_name']
            );

            $allowed = [

                'image/jpeg' => 'jpg',

                'image/png' => 'png',

                'image/webp' => 'webp',

                'image/gif' => 'gif'

            ];

            $mime = $info['mime'] ?? '';

            if (
                $info === false ||
                !isset($allowed[$mime])
            ) {

                $error =
                    'Only JPG, PNG, WEBP or GIF images are allowed.';

            } else {

                $newImageName =
                    'category_' .
                    bin2hex(random_bytes(12)) .
                    '.' .
                    $allowed[$mime];

                $directory =
                    __DIR__ .
                    '/../assets/images/categories/';

                if (!is_dir($directory)) {

                    mkdir(
                        $directory,
                        0755,
                        true
                    );

                }

                if (!move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $directory . $newImageName
                )) {

                    $newImageName =
                        $category['image'];

                    $error =
                        'Unable to save the category image.';

                }

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Save Database
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        try {

            $stmt = $pdo->prepare("
                UPDATE categories
                SET
                    name = :name,
                    slug = :slug,
                    image = :image,
                    description = :description,
                    sort_order = :sort_order,
                    is_active = :is_active,
                    show_on_homepage = :show_on_homepage
                WHERE id = :id
            ");

            $stmt->execute([

                'name' =>
                    $name,

                'slug' =>
                    $slug,

                'image' =>
                    $newImageName,

                'description' =>
                    $description !== ''
                        ? $description
                        : null,

                'sort_order' =>
                    $sortOrder,

                'is_active' =>
                    $isActive,

                'show_on_homepage' =>
                    $showOnHomepage,

                'id' =>
                    $categoryId

            ]);


            /*
            |--------------------------------------------------------------------------
            | Remove Old Image
            |--------------------------------------------------------------------------
            */

            if (
                $newImageName !== $category['image'] &&
                !empty($category['image'])
            ) {

                $oldPath =
                    __DIR__ .
                    '/../assets/images/categories/' .
                    $category['image'];

                if (file_exists($oldPath)) {

                    unlink($oldPath);

                }

            }


            header(
                'Location: categories.php?updated=1'
            );

            exit;


        } catch (Throwable $e) {


            /*
            |--------------------------------------------------------------------------
            | Remove Newly Uploaded Image
            |--------------------------------------------------------------------------
            */

            if (
                $newImageName !== $category['image'] &&
                file_exists(
                    __DIR__ .
                    '/../assets/images/categories/' .
                    $newImageName
                )
            ) {

                unlink(
                    __DIR__ .
                    '/../assets/images/categories/' .
                    $newImageName
                );

            }


            $error =
                'Unable to update category. Please try again.';

        }

    }

}


/*
|--------------------------------------------------------------------------
| Current Values After Validation
|--------------------------------------------------------------------------
*/

$currentName =
    $_POST['name']
    ?? $category['name'];

$currentSlug =
    $_POST['slug']
    ?? $category['slug'];

$currentDescription =
    $_POST['description']
    ?? ($category['description'] ?? '');

$currentSortOrder =
    $_POST['sort_order']
    ?? ($category['sort_order'] ?? 0);

$currentActive =
    $_SERVER['REQUEST_METHOD'] === 'POST'
        ? isset($_POST['is_active'])
        : ((int)$category['is_active'] === 1);

$currentHomepage =
    $_SERVER['REQUEST_METHOD'] === 'POST'
        ? isset($_POST['show_on_homepage'])
        : ((int)$category['show_on_homepage'] === 1);

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
    Edit Category — Nitya Ritual E-Store
</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;

    font-family:
        Arial,
        sans-serif;

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

    color: #fff;
}

.sidebar-brand {
    padding: 25px 20px;

    font-size: 20px;

    font-weight: bold;

    border-bottom:
        1px solid
        rgba(255,255,255,.15);
}

.sidebar nav {
    padding: 20px 0;
}

.sidebar a {
    display: block;

    padding: 13px 20px;

    color: #fff;

    text-decoration: none;
}

.sidebar a:hover,
.sidebar a.active {
    background:
        rgba(255,255,255,.12);
}

.main {
    margin-left: 240px;

    min-height: 100vh;
}

.topbar {
    background: #fff;

    padding: 18px 30px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    border-bottom:
        1px solid #ddd;
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

    max-width: 900px;
}

.form-box {
    background: #fff;

    border:
        1px solid #e5e5e5;

    border-radius: 10px;

    padding: 25px;
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

input,
select,
textarea {
    width: 100%;

    padding: 11px 12px;

    border:
        1px solid #ccc;

    border-radius: 6px;

    font: inherit;

    background: #fff;
}

textarea {
    resize: vertical;

    min-height: 100px;
}

input[type="checkbox"] {
    width: auto;
}

.check-row {
    display: flex;

    align-items: center;

    gap: 9px;

    font-weight: normal;
}

.help {
    color: #777;

    font-size: 12px;

    margin-top: 5px;
}

.alert {
    padding: 12px 15px;

    border-radius: 6px;

    margin-bottom: 18px;

    background: #fde8e8;

    color: #a61b1b;
}

.current-image {
    width: 120px;

    height: 120px;

    object-fit: cover;

    border-radius: 10px;

    border:
        1px solid #ddd;

    margin-bottom: 10px;

    display: block;
}

.actions {
    display: flex;

    gap: 10px;

    margin-top: 25px;
}

.btn {
    border: 0;

    border-radius: 6px;

    padding: 11px 16px;

    background: #8B1E1E;

    color: #fff;

    cursor: pointer;

    font-size: 14px;

    text-decoration: none;
}

.btn-secondary {
    background: #eee;

    color: #333;
}

@media(max-width:700px) {

    .sidebar {
        width: 190px;
    }

    .main {
        margin-left: 190px;
    }

    .content {
        padding: 18px;
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

        <a
            href="categories.php"
            class="active"
        >
            Categories
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
            Edit Category
        </h2>


        <div class="admin-info">

            <span>
                <?= e(
                    $_SESSION['admin_name']
                    ?? 'Admin'
                ) ?>
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

        <h1>
            Edit Category
        </h1>


        <div class="form-box">


            <?php if ($error !== ''): ?>

                <div class="alert">

                    <?= e($error) ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                enctype="multipart/form-data"
            >

            <?= csrf_field() ?>


                <input
                    type="hidden"
                    name="id"
                    value="<?= $categoryId ?>"
                >


                <div class="form-group">

                    <label>
                        Category Name *
                    </label>


                    <input
                        type="text"
                        name="name"
                        value="<?= e($currentName) ?>"
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
                        value="<?= e($currentSlug) ?>"
                        required
                    >


                    <div class="help">

                        Changing this changes
                        the category URL.

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Category Image
                    </label>


                    <?php if (
                        !empty($category['image'])
                    ): ?>

                        <img
                            src="../assets/images/categories/<?= e($category['image']) ?>"
                            alt="<?= e($category['name']) ?>"
                            class="current-image"
                        >

                    <?php endif; ?>


                    <input
                        type="file"
                        name="image"
                        accept="
                            image/jpeg,
                            image/png,
                            image/webp,
                            image/gif
                        "
                    >


                    <div class="help">

                        Upload a new image only if
                        you want to replace the current one.
                        Maximum 5 MB.

                    </div>

                </div>


                <div class="form-group">

                    <label>
                        Description
                    </label>


                    <textarea
                        name="description"
                    ><?= e($currentDescription) ?></textarea>

                </div>


                <div class="form-group">

                    <label>
                        Display Order
                    </label>


                    <input
                        type="number"
                        name="sort_order"
                        min="0"
                        value="<?= e($currentSortOrder) ?>"
                    >

                </div>


                <div class="form-group">

                    <label class="check-row">

                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            <?= $currentActive
                                ? 'checked'
                                : '' ?>
                        >

                        Active

                    </label>

                </div>


                <div class="form-group">

                    <label class="check-row">

                        <input
                            type="checkbox"
                            name="show_on_homepage"
                            value="1"
                            <?= $currentHomepage
                                ? 'checked'
                                : '' ?>
                        >

                        Show on Homepage

                    </label>

                </div>


                <div class="actions">

                    <button
                        type="submit"
                        class="btn"
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