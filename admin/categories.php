<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();


/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function e($value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| Add Category
|--------------------------------------------------------------------------
*/

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

$name = request_string($_POST['name'] ?? null);
$slug = request_string($_POST['slug'] ?? null);

$image = null;

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

        $error = 'Image upload failed. Please try again.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | Validate image size
        |--------------------------------------------------------------------------
        */

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {

            $error = 'Image must be smaller than 5 MB.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Validate actual MIME type
            |--------------------------------------------------------------------------
            */

            $finfo = new finfo(FILEINFO_MIME_TYPE);

            $mime = $finfo->file(
                $_FILES['image']['tmp_name']
            );

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif'
            ];

            if (!isset($allowedTypes[$mime])) {

                $error =
                    'Invalid image. Only JPG, PNG, WEBP and GIF are allowed.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | Upload directory
                |--------------------------------------------------------------------------
                */

                $uploadDir =
                    __DIR__ .
                    '/../assets/images/categories/';

                if (!is_dir($uploadDir)) {

                    mkdir(
                        $uploadDir,
                        0755,
                        true
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Generate random filename
                |--------------------------------------------------------------------------
                */

                $filename =
                    'category_' .
                    bin2hex(random_bytes(16)) .
                    '.' .
                    $allowedTypes[$mime];

                $destination =
                    $uploadDir . $filename;


                /*
                |--------------------------------------------------------------------------
                | Move uploaded file
                |--------------------------------------------------------------------------
                */

                if (
                    !move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        $destination
                    )
                ) {

                    $error =
                        'Unable to save the uploaded image.';

                } else {

                    $image = $filename;

                }

            }

        }

    }

}

$description = request_string(
    $_POST['description'] ?? null
);

$sortOrder = filter_var(
    $_POST['sort_order'] ?? null,
    FILTER_VALIDATE_INT
);

$sortOrder = $sortOrder !== false && $sortOrder !== null
    ? max(0, $sortOrder)
    : 0;

    $isActive = isset($_POST['is_active'])
        ? 1
        : 0;

    $showOnHomepage = isset($_POST['show_on_homepage'])
        ? 1
        : 0;


    /*
    |--------------------------------------------------------------------------
    | Validate Name
    |--------------------------------------------------------------------------
    */

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

        if (
    $slug === '' ||
    !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)
) {
    $error = 'Please enter a valid category slug.';
}


        if ($slug === '') {

            $error =
                'Please enter a valid category name or slug.';

        } else {


            /*
            |--------------------------------------------------------------------------
            | Duplicate Slug
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

                $error =
                    'A category with this slug already exists.';

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
                        slug,
                        image,
                        description,
                        sort_order,
                        is_active,
                        show_on_homepage
                    )

                    VALUES
                    (
                        :name,
                        :slug,
                        :image,
                        :description,
                        :sort_order,
                        :is_active,
                        :show_on_homepage
                    )
                ");


                $stmt->execute([

                    'name' =>
                        $name,

                    'slug' =>
                        $slug,

                    'image' => $image,

                    'description' =>
                        $description !== ''
                            ? $description
                            : null,

                    'sort_order' =>
                        $sortOrder,

                    'is_active' =>
                        $isActive,

                    'show_on_homepage' =>
                        $showOnHomepage

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
| Get Categories
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT
        c.id,
        c.name,
        c.slug,
        c.image,
        c.description,
        c.sort_order,
        c.is_active,
        c.show_on_homepage,

        COUNT(p.id) AS product_count

    FROM categories c

    LEFT JOIN products p
        ON p.category_id = c.id

    GROUP BY
        c.id,
        c.name,
        c.slug,
        c.image,
        c.description,
        c.sort_order,
        c.is_active,
        c.show_on_homepage

    ORDER BY
    c.is_active DESC,
    c.sort_order ASC,
    c.name ASC
");

$categories = $stmt->fetchAll(
    PDO::FETCH_ASSOC
);

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
    Categories — Nitya Ritual E-Store
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

    color: white;

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

    color: white;

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

    background: white;

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

}


.page-head {

    margin-bottom: 25px;

}


.page-head h1 {

    margin:
        0 0 7px;

}


.page-head p {

    margin: 0;

    color: #666;

}


.grid {

    display: grid;

    grid-template-columns:
        380px 1fr;

    gap: 25px;

    align-items: start;

}


.box {

    background: white;

    border:
        1px solid #e5e5e5;

    border-radius: 10px;

    overflow: hidden;

}


.box-head {

    padding: 18px 20px;

    border-bottom:
        1px solid #eee;

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


input,
select,
textarea {

    width: 100%;

    padding: 11px 12px;

    border:
        1px solid #ccc;

    border-radius: 6px;

    font: inherit;

}


textarea {

    resize: vertical;

}


.help {

    color: #777;

    font-size: 12px;

    margin-top: 5px;

}


.checkbox-group {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 15px;

}


.checkbox-group input {

    width: auto;

}


.checkbox-group label {

    margin: 0;

    font-weight: normal;

}


.btn {

    border: 0;

    border-radius: 6px;

    padding: 9px 13px;

    background: #8B1E1E;

    color: white;

    cursor: pointer;

    font-size: 13px;

    text-decoration: none;

    display: inline-block;

}


.btn:hover {

    opacity: .9;

}


.btn-edit {

    background: #f1f1f1;

    color: #333;

    border:
        1px solid #ddd;

}


.btn-delete {

    background: #8B1E1E;

}


.btn-delete:disabled {

    opacity: .45;

    cursor: not-allowed;

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

    border-bottom:
        1px solid #eee;

    vertical-align: middle;

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


.category-image {

    width: 45px;

    height: 45px;

    object-fit: cover;

    border-radius: 6px;

    display: block;

}


.no-image {

    width: 45px;

    height: 45px;

    border-radius: 6px;

    background: #f3f3f3;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 20px;

}


.status {

    display: inline-block;

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: bold;

}


.status-active {

    background: #e4f7e8;

    color: #1d7a35;

}


.status-inactive {

    background: #fde8e8;

    color: #a61b1b;

}


.home-yes {

    color: #1d7a35;

    font-weight: bold;

}


.home-no {

    color: #999;

}


.actions {

    display: flex;

    gap: 7px;

    align-items: center;

}


.actions form {

    margin: 0;

}


@media (max-width: 1100px) {

    .grid {

        grid-template-columns: 1fr;

    }

}

/* =========================================
   Admin Mobile Sidebar
========================================= */

.topbar-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.menu-toggle {
    display: none;
    border: 0;
    background: transparent;
    color: #8B1E1E;
    font-size: 26px;
    line-height: 1;
    padding: 0;
    cursor: pointer;
}

.sidebar-overlay {
    display: none;
}


/* =========================================
   Mobile
========================================= */

@media (max-width: 700px) {

    /* =====================================
       Category Table
    ===================================== */

    table {
        width: 100%;
        table-layout: fixed;
    }

    th,
    td {
        padding: 11px 8px;
        font-size: 12px;
    }

    /* Hide slug */

    th:nth-child(3),
    td:nth-child(3) {
        display: none;
    }

    /* Hide homepage */

    th:nth-child(6),
    td:nth-child(6) {
        display: none;
    }

    /* Image */

    th:nth-child(1),
    td:nth-child(1) {
        width: 48px;
    }

    .category-image,
    .no-image {
        width: 40px;
        height: 40px;
    }

    .no-image {
        font-size: 17px;
    }

    /* Category */

    th:nth-child(2),
    td:nth-child(2) {
        width: auto;
    }

    td:nth-child(2) strong {
        display: block;
        font-size: 12px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Products */

    th:nth-child(4),
    td:nth-child(4) {
        width: 55px;
        text-align: center;
    }

    .count {
        padding: 4px 7px;
        font-size: 11px;
    }

    /* Status */

    th:nth-child(5),
    td:nth-child(5) {
        width: 65px;
        text-align: center;
    }

    .status {
        padding: 4px 6px;
        font-size: 10px;
    }

    /* Actions */

    th:nth-child(7),
    td:nth-child(7) {
        width: 95px;
    }

    .actions {
        display: flex;
        gap: 4px;
    }

    .actions .btn {
        padding: 6px 7px;
        font-size: 10px;
        white-space: nowrap;
    }

    .actions form {
        flex: 1;
    }

    /* Sidebar */

    .sidebar {
        width: 260px;
        height: 100vh;
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
        min-width: 0;
    }

    /* Topbar */

    .topbar {
        padding: 14px 16px;
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
        font-size: 19px;
    }

    .admin-info {
        gap: 8px;
    }

    .admin-info span {
        display: none;
    }

    .admin-info button {
        padding: 7px 10px;
        font-size: 12px;
    }

    /* Content */

    .content {
        padding: 18px 12px;
    }

    .page-head {
        margin-bottom: 18px;
    }

    .page-head h1 {
        font-size: 23px;
        margin-bottom: 5px;
    }

    .page-head p {
        font-size: 13px;
    }

    /* Add Category + Categories */

    .grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .box {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    .box-head {
        padding: 15px;
    }

    .box-head h3 {
        font-size: 16px;
    }

    .box-body {
        padding: 15px;
    }

    /* Form */

    label {
        font-size: 13px;
    }

    input,
    select,
    textarea {
        font-size: 14px;
        padding: 11px;
    }

    textarea {
        min-height: 120px;
    }

    .help {
        font-size: 11px;
        line-height: 1.5;
    }

    /* Add button */

    .box-body .btn {
        width: 100%;
        text-align: center;
        padding: 11px;
    }

}

.category-row {
    cursor: pointer;
}

.category-row:hover {
    background: #fafafa;
}

@media (max-width: 700px) {

    .category-row:active {
        background: #f3f3f3;
    }

    /* Hide Edit button on phone */

    .category-row .btn-edit {
        display: none;
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
   Category Delete Modal
========================================= */

.delete-modal {
    position: fixed;
    inset: 0;

    display: none;

    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(0, 0, 0, 0.5);

    z-index: 2000;
}

.delete-modal.show {
    display: flex;
}

.delete-modal-box {
    width: 100%;
    max-width: 470px;

    max-height: calc(100vh - 40px);
    overflow-y: auto;

    background: #fff;

    border-radius: 14px;

    padding: 28px;

    text-align: center;

    box-shadow:
        0 20px 50px rgba(0, 0, 0, .20);
}


/* Icon */

.delete-icon {
    width: 48px;
    height: 48px;

    margin: 0 auto 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #fde8e8;

    color: #b42318;

    font-size: 21px;
    font-weight: bold;
}


/* Heading */

.delete-modal-box h3 {
    margin: 0 0 10px;

    font-size: 21px;
    color: #222;
}

.delete-modal-box p {
    margin: 8px 0;

    color: #555;

    font-size: 14px;

    line-height: 1.5;
}


/* Options */

.delete-option {
    width: 100%;

    margin-top: 12px;

    padding: 14px;

    text-align: left;

    background: #fafafa;

    border: 1px solid #e5e5e5;

    border-radius: 9px;
}


/*
   IMPORTANT:
   The span gives the text its own flex area.
*/

.delete-option label {
    width: 100%;

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin: 0;

    padding: 0;

    cursor: pointer;

    font-size: 13px;

    font-weight: 600;

    line-height: 1.45;

    text-align: left;
}


.delete-option label span {
    display: block;

    flex: 1;

    min-width: 0;

    width: auto;

    margin: 0;

    padding: 0;

    text-align: left;
}


.delete-option input[type="radio"] {
    width: 17px;
    height: 17px;

    min-width: 17px;

    margin: 2px 0 0 0;

    padding: 0;

    flex: 0 0 17px;
}


.delete-option select {
    display: block;

    width: calc(100% - 27px);

    margin: 11px 0 0 27px;

    padding: 10px;

    border: 1px solid #ccc;

    border-radius: 6px;

    background: #fff;

    font: inherit;

    font-size: 13px;
}

.delete-option select:disabled {
    background: #f1f1f1;

    color: #999;
}


/* Warning */

.force-warning {
    color: #b42318 !important;

    font-size: 12px !important;

    margin-top: 14px !important;
}


/* Buttons */

.delete-modal-actions {
    display: flex;

    gap: 10px;

    margin-top: 22px;
}

.modal-cancel,
.modal-confirm {
    flex: 1;

    min-width: 0;

    padding: 11px 14px;

    border-radius: 7px;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;
}

.modal-cancel {
    border: 1px solid #ddd;

    background: #f5f5f5;

    color: #333;
}

.modal-confirm {
    border: 0;

    background: #8B1E1E;

    color: #fff;
}

.modal-confirm:hover {
    background: #721818;
}


/* =========================================
   Mobile
========================================= */

@media (max-width: 700px) {

    .delete-modal {
        padding: 12px;
    }

    .delete-modal-box {
        width: 100%;

        max-width: 100%;

        max-height: calc(100vh - 24px);

        padding: 22px 16px;

        border-radius: 11px;
    }

    .delete-icon {
        width: 44px;
        height: 44px;

        font-size: 19px;

        margin-bottom: 12px;
    }

    .delete-modal-box h3 {
        font-size: 19px;
    }

    .delete-modal-box p {
        font-size: 13px;
    }

    .delete-option {
        padding: 12px;

        margin-top: 10px;
    }

    .delete-option label {
        gap: 9px;

        font-size: 12px;

        line-height: 1.45;
    }

    .delete-option input[type="radio"] {
        width: 16px;
        height: 16px;

        min-width: 16px;

        flex-basis: 16px;
    }

    .delete-option select {
        width: calc(100% - 25px);

        margin-left: 25px;

        font-size: 12px;

        padding: 9px;
    }

    .force-warning {
        font-size: 11px !important;
    }

    .delete-modal-actions {
        gap: 8px;

        margin-top: 18px;
    }

    .modal-cancel,
    .modal-confirm {
        padding: 10px 8px;

        font-size: 12px;
    }
}


</style>

</head>


<body>


<!-- =========================================================
     SIDEBAR
========================================================= -->

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


<div
    class="sidebar-overlay"
    onclick="toggleSidebar()"
></div>

<!-- =========================================================
     MAIN
========================================================= -->

<div class="main">


    <!-- TOPBAR -->

<div class="topbar">

    <div class="topbar-left">

        <button
            type="button"
            class="menu-toggle"
            onclick="toggleSidebar()"
            aria-label="Open menu"
        >
            ☰
        </button>

        <h2>
            Categories
        </h2>

    </div>


        <div class="admin-info">


            <span>

                <?= e(
                    $_SESSION['admin_name']
                    ?? 'Admin'
                ) ?>

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


    <!-- CONTENT -->

    <div class="content">


        <div class="page-head">


            <h1>
                Manage Categories
            </h1>


            <p>
                Create and manage your store categories.
            </p>


        </div>


        <!-- =================================================
             MESSAGES
        ================================================== -->


        <?php if ($error !== ''): ?>

            <div class="alert alert-error">

                <?= e($error) ?>

            </div>

        <?php endif; ?>


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


        <?php if (
            isset($_GET['delete_error'])
        ): ?>

            <div class="alert alert-error">

                <?= e($_GET['delete_error']) ?>

            </div>

        <?php endif; ?>


        <!-- =================================================
             TWO COLUMN AREA
        ================================================== -->

        <div class="grid">


            <!-- =============================================
                 ADD CATEGORY
            ============================================== -->

            <div class="box">


                <div class="box-head">

                    <h3>
                        Add Category
                    </h3>

                </div>


                <div class="box-body">


                    <form method="POST" enctype="multipart/form-data">
                        

                    <?= csrf_field() ?>


                        <div class="form-group">

                            <label>
                                Category Name *
                            </label>


                            <input
                                type="text"
                                name="name"
                                id="category-name"
                                value="<?= e(
                                    $_POST['name']
                                    ?? ''
                                ) ?>"
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
                                value="<?= e(
                                    $_POST['slug']
                                    ?? ''
                                ) ?>"
                            >


                            <div class="help">

                                Leave empty to generate
                                automatically.

                            </div>

                        </div>


                        <div class="form-group">

    <label>
        Category Image
    </label>

    <input
        type="file"
        name="image"
        accept="image/jpeg,image/png,image/webp,image/gif"
    >

    <div class="help">
        Upload JPG, PNG, WEBP or GIF. Maximum size: 5 MB.
    </div>

</div>


                        <div class="form-group">

                            <label>
                                Description
                            </label>


                            <textarea
                                name="description"
                                rows="4"
                                placeholder="Short description of this category"
                            ><?= e(
                                $_POST['description']
                                ?? ''
                            ) ?></textarea>

                        </div>


                        <div class="form-group">

                            <label>
                                Display Order
                            </label>


                            <input
                                type="number"
                                name="sort_order"
                                value="<?= e(
                                    $_POST['sort_order']
                                    ?? '0'
                                ) ?>"
                            >

                        </div>


                        <div class="checkbox-group">


                            <input
                                type="checkbox"
                                name="is_active"
                                id="is-active"
                                <?= (
                                    !isset($_POST['is_active']) ||
                                    isset($_POST['is_active'])
                                )
                                    ? 'checked'
                                    : ''
                                ?>
                            >


                            <label for="is-active">

                                Active

                            </label>


                        </div>


                        <div class="checkbox-group">


                            <input
                                type="checkbox"
                                name="show_on_homepage"
                                id="show-home"
                                <?= isset(
                                    $_POST['show_on_homepage']
                                )
                                    ? 'checked'
                                    : ''
                                ?>
                            >


                            <label for="show-home">

                                Show on Homepage

                            </label>


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


            <!-- =============================================
                 CATEGORY LIST
            ============================================== -->

            <div class="box">


                <div class="box-head">


                    <h3>

                        Categories
                        (<?= count($categories) ?>)

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

                                <th>
                                    Image
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Slug
                                </th>

                                <th>
                                    Products
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Homepage
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php foreach (
                            $categories
                            as $category
                        ): ?>


                            <tr class="category-row"
    onclick="window.location.href='edit-category.php?id=<?= (int)$category['id'] ?>'">


                                <!-- IMAGE -->

                                <td>


                                    <?php if (
                                        !empty(
                                            $category['image']
                                        )
                                    ): ?>


                                        <img
                                            src="../assets/images/categories/<?= rawurlencode(
                                                $category['image']
                                            ) ?>"
                                            alt="<?= e(
                                                $category['name']
                                            ) ?>"
                                            class="category-image"
                                        >


                                    <?php else: ?>


                                        <div class="no-image">
                                            🪔
                                        </div>


                                    <?php endif; ?>


                                </td>


                                <!-- NAME -->

                                <td>


                                    <strong>

                                        <?= e(
                                            $category['name']
                                        ) ?>

                                    </strong>


                                </td>


                                <!-- SLUG -->

                                <td class="slug">

                                    <?= e(
                                        $category['slug']
                                    ) ?>

                                </td>


                                <!-- PRODUCTS -->

                                <td>


                                    <span class="count">

                                        <?= (int)$category[
                                            'product_count'
                                        ] ?>

                                    </span>


                                </td>


                                <!-- STATUS -->

                                <td>


                                    <?php if (
                                        (int)$category[
                                            'is_active'
                                        ] === 1
                                    ): ?>


                                        <span
                                            class="status status-active"
                                        >

                                            Active

                                        </span>


                                    <?php else: ?>


                                        <span
                                            class="status status-inactive"
                                        >

                                            Inactive

                                        </span>


                                    <?php endif; ?>


                                </td>


                                <!-- HOMEPAGE -->

                                <td>


                                    <?php if (
                                        (int)$category[
                                            'show_on_homepage'
                                        ] === 1
                                    ): ?>


                                        <span class="home-yes">
                                            Yes
                                        </span>


                                    <?php else: ?>


                                        <span class="home-no">
                                            No
                                        </span>


                                    <?php endif; ?>


                                </td>


                                <!-- ACTIONS -->

                                <td>


                                    <div class="actions">


                                        <a
                                            href="edit-category.php?id=<?= (int)$category['id'] ?>"
                                            class="btn btn-edit"
                                        >

                                            Edit

                                        </a>


                                        <form
    method="POST"
    action="delete-category.php"
    class="delete-category-form"
    onclick="event.stopPropagation()"
>
                                            <?= csrf_field() ?>

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$category['id'] ?>"
                                            >

<button
    type="button"
    class="btn btn-delete"
    onclick="
        event.stopPropagation();
        openCategoryDeleteModal(
            <?= (int)$category['id'] ?>,
            '<?= e($category['name']) ?>',
            <?= (int)$category['product_count'] ?>
        );
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

/*
|--------------------------------------------------------------------------
| Automatic Slug
|--------------------------------------------------------------------------
*/

const nameInput =
    document.getElementById(
        "category-name"
    );


const slugInput =
    document.getElementById(
        "category-slug"
    );


let slugManuallyEdited =
    slugInput.value.trim() !== "";


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
                .replace(
                    /[^a-z0-9]+/g,
                    "-"
                )
                .replace(
                    /^-+|-+$/g,
                    ""
                );

    }
);


function toggleSidebar() {

    const sidebar = document.querySelector('.sidebar');

    sidebar.classList.toggle('open');

}


let deleteCategoryForm = null;

function openCategoryDeleteModal(
    categoryId,
    categoryName,
    productCount
) {

    document.getElementById(
        'deleteCategoryId'
    ).value = categoryId;

    document.getElementById(
        'deleteCategoryName'
    ).textContent = categoryName;

    document.getElementById(
        'deleteProductCount'
    ).textContent = productCount;


    const emptyContent =
        document.getElementById(
            'emptyCategoryContent'
        );

    const productsContent =
        document.getElementById(
            'categoryProductsContent'
        );


    if (productCount === 0) {

        emptyContent.style.display = 'block';

        productsContent.style.display = 'none';

        document.getElementById(
            'deleteMode'
        ).value = 'empty';


        document.getElementById(
            'confirmCategoryDelete'
        ).textContent =
            'Delete Category';

    } else {

        emptyContent.style.display = 'none';

        productsContent.style.display = 'block';


        document.querySelector(
            'input[name="categoryDeleteMode"][value="move"]'
        ).checked = true;


        updateDeleteMode();

    }


    document
        .getElementById('categoryDeleteModal')
        .classList.add('show');

}


function updateDeleteMode() {

    const selected =
        document.querySelector(
            'input[name="categoryDeleteMode"]:checked'
        );

    if (!selected) {
        return;
    }


    const mode = selected.value;

    document.getElementById(
        'deleteMode'
    ).value = mode;


    const select =
        document.getElementById(
            'moveToCategory'
        );


    select.disabled =
        mode !== 'move';


    document.getElementById(
        'confirmCategoryDelete'
    ).textContent =
        mode === 'force'
            ? 'Delete Everything'
            : 'Continue';

}


function confirmCategoryDelete() {

    const mode =
        document.getElementById(
            'deleteMode'
        ).value;


    if (mode === 'move') {

        const destination =
            document.getElementById(
                'moveToCategory'
            ).value;


        if (!destination) {

            alert(
                'Please select a category to move the products to.'
            );

            return;
        }


        document.getElementById(
            'moveToCategoryId'
        ).value = destination;

    }


    document
        .getElementById('categoryDeleteForm')
        .submit();

}


function closeCategoryDeleteModal() {

    document
        .getElementById('categoryDeleteModal')
        .classList.remove('show');

}

</script>

<div
    id="categoryDeleteModal"
    class="delete-modal"
    aria-hidden="true"
>

    <div class="delete-modal-box">

        <div class="delete-icon">
            !
        </div>

        <h3>Delete Category?</h3>

        <p>
            You are deleting
            <strong id="deleteCategoryName"></strong>.
        </p>


        <!-- EMPTY CATEGORY -->

        <div id="emptyCategoryContent">

            <p>
                This category has no products.
                It can be safely deleted.
            </p>

        </div>


        <!-- CATEGORY WITH PRODUCTS -->

        <div id="categoryProductsContent">

            <p>
                This category contains
                <strong id="deleteProductCount"></strong>
                products.
            </p>


            <div class="delete-option">

<label>

    <input
        type="radio"
        name="categoryDeleteMode"
        value="move"
        checked
        onchange="updateDeleteMode()"
    >

    <span>
        Move products to another category
    </span>

</label>


                <select id="moveToCategory">

                    <option value="">
                        Select category
                    </option>

<?php foreach ($categories as $destination): ?>

    <?php if (
        (int)$destination['id'] ===
        (int)($categoryId ?? 0)
    ) {
        continue;
    } ?>

    <option
        value="<?= (int)$destination['id'] ?>"
    >
        <?= e($destination['name']) ?>
    </option>

<?php endforeach; ?>

                </select>

            </div>


            <div class="delete-option">

<label>

    <input
        type="radio"
        name="categoryDeleteMode"
        value="force"
        onchange="updateDeleteMode()"
    >

    <span>
        Force delete category and all products
    </span>

</label>

            </div>


            <p class="force-warning">
                Force deleting permanently removes the
                products and their images.
            </p>

        </div>


        <form
            method="POST"
            action="delete-category.php"
            id="categoryDeleteForm"
        >

            <?= csrf_field() ?>

            <input
    type="hidden"
    name="confirm_delete"
    value="1"
>

            <input
                type="hidden"
                name="id"
                id="deleteCategoryId"
            >

            <input
                type="hidden"
                name="delete_mode"
                id="deleteMode"
            >

<input
    type="hidden"
    name="replacement_category_id"
    id="moveToCategoryId"
>

        </form>


        <div class="delete-modal-actions">

            <button
                type="button"
                class="modal-cancel"
                onclick="closeCategoryDeleteModal()"
            >
                Cancel
            </button>

            <button
                type="button"
                class="modal-confirm"
                id="confirmCategoryDelete"
                onclick="confirmCategoryDelete()"
            >
                Delete Category
            </button>

        </div>

    </div>

</div>

</body>

</html>