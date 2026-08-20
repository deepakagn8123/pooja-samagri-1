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

$image = request_string($_POST['image'] ?? null);

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

                    'image' =>
                        $image !== ''
                            ? $image
                            : null,

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


@media (max-width: 800px) {

    .sidebar {

        width: 190px;

    }

    .main {

        margin-left: 190px;

    }

    .content {

        padding: 20px;

    }

}


@media (max-width: 650px) {

    .sidebar {

        position: static;

        width: 100%;

        height: auto;

    }

    .main {

        margin-left: 0;

    }

    .topbar {

        padding: 15px 20px;

    }

    .grid {

        grid-template-columns: 1fr;

    }

    table {

        min-width: 900px;

    }

    .box {

        overflow-x: auto;

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


<!-- =========================================================
     MAIN
========================================================= -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">


        <h2>
            Categories
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


                    <form method="POST">

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
                                type="text"
                                name="image"
                                value="<?= e(
                                    $_POST['image']
                                    ?? ''
                                ) ?>"
                                placeholder="example.webp"
                            >


                            <div class="help">

                                Put the image inside:
                                assets/images/categories/

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


                            <tr>


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
                                            onsubmit="
                                                return confirm(
                                                    'Delete this category?'
                                                );
                                            "
                                        >

                                            <?= csrf_field() ?>

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$category['id'] ?>"
                                            >


                                            <button
                                                type="submit"
                                                class="btn btn-delete"
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

</script>


</body>

</html>