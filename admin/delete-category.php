<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

verify_csrf();


/*
|--------------------------------------------------------------------------
| Get Category ID
|--------------------------------------------------------------------------
*/

$categoryId = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($categoryId < 1) {

    header('Location: categories.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Get Category
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        name,
        slug,
        image
    FROM categories
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    'id' => $categoryId
]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$category) {

    header('Location: categories.php?delete_error=' . urlencode('Category not found.'));
    exit;

}


/*
|--------------------------------------------------------------------------
| Count Products
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM products
    WHERE category_id = :category_id
");

$stmt->execute([
    'category_id' => $categoryId
]);

$productCount = (int)$stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Confirmed Delete
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['confirm_delete'])
) {

    $replacementId = (int)($_POST['replacement_category_id'] ?? 0);


    /*
    |--------------------------------------------------------------------------
    | If Products Exist, Validate Replacement Category
    |--------------------------------------------------------------------------
    */

    if ($productCount > 0) {

        if (
            $replacementId < 1 ||
            $replacementId === $categoryId
        ) {

            header(
                'Location: categories.php?delete_error=' .
                urlencode('Please select a valid replacement category.')
            );

            exit;
        }


        /*
        | Make sure replacement category exists
        */

        $stmt = $pdo->prepare("
            SELECT id
            FROM categories
            WHERE id = :id
            AND is_active = 1
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $replacementId
        ]);

        if (!$stmt->fetch()) {

            header(
                'Location: categories.php?delete_error=' .
                urlencode('Replacement category does not exist.')
            );

            exit;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Delete Category Safely
    |--------------------------------------------------------------------------
    */

    try {

        $pdo->beginTransaction();


        /*
        | Move existing products to replacement category
        */

        if ($productCount > 0) {

            $stmt = $pdo->prepare("
                UPDATE products
                SET category_id = :replacement_id
                WHERE category_id = :category_id
            ");

            $stmt->execute([
                'replacement_id' => $replacementId,
                'category_id' => $categoryId
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Delete Category
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            DELETE FROM categories
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $categoryId
        ]);


        /*
        |--------------------------------------------------------------------------
        | Delete Category Image
        |--------------------------------------------------------------------------
        */

        if (!empty($category['image'])) {

            $imagePath =
                __DIR__ .
                '/../assets/images/categories/' .
                $category['image'];


            /*
            | Only delete the image if it actually exists.
            */

            if (is_file($imagePath)) {

                @unlink($imagePath);

            }

        }


        $pdo->commit();


        header('Location: categories.php?deleted=1');
        exit;


    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {

            $pdo->rollBack();

        }


        header(
            'Location: categories.php?delete_error=' .
            urlencode('Category could not be deleted. No data was changed.')
        );

        exit;

    }

}


/*
|--------------------------------------------------------------------------
| Get Replacement Categories
|--------------------------------------------------------------------------
|
| These are the categories where existing products can be moved.
|
*/

$replacementCategories = [];


if ($productCount > 0) {

    $stmt = $pdo->prepare("
        SELECT
            id,
            name
        FROM categories
        WHERE id != :id
        AND is_active = 1
        ORDER BY name ASC
    ");

    $stmt->execute([
        'id' => $categoryId
    ]);

    $replacementCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

}


function e($value): string
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

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
        Delete Category — Nitya Ritual E-Store
    </title>


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


        .box {

            width: min(
                600px,
                calc(100% - 30px)
            );

            margin: 80px auto;

            background: #fff;

            border: 1px solid #ddd;

            border-radius: 12px;

            padding: 30px;

        }


        h1 {

            margin-top: 0;

            color: #8B1E1E;

        }


        .warning {

            background: #fff4d6;

            border: 1px solid #f0d98c;

            color: #6f5500;

            border-radius: 8px;

            padding: 15px;

            line-height: 1.6;

            margin-bottom: 25px;

        }


        .danger {

            background: #fdeaea;

            border: 1px solid #e2aaaa;

            color: #8B1E1E;

            border-radius: 8px;

            padding: 15px;

            line-height: 1.6;

            margin-bottom: 20px;

        }


        select {

            width: 100%;

            padding: 11px;

            border: 1px solid #ccc;

            border-radius: 6px;

            margin: 8px 0 20px;

            font: inherit;

        }


        label {

            display: block;

            font-weight: bold;

        }


        .actions {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

        }


        button,
        a {

            padding: 11px 16px;

            border-radius: 6px;

            border: 0;

            text-decoration: none;

            font: inherit;

            cursor: pointer;

        }


        button {

            background: #8B1E1E;

            color: #fff;

        }


        button:hover {

            background: #6F1515;

        }


        a {

            background: #eee;

            color: #333;

        }


        a:hover {

            background: #ddd;

        }

    </style>

</head>


<body>


<div class="box">


    <h1>
        Delete Category
    </h1>


    <?php if ($productCount > 0): ?>


        <div class="warning">

            <strong>
                <?= e($category['name']) ?>
            </strong>

            currently contains

            <strong>
                <?= $productCount ?>
            </strong>

            product(s).

            <br><br>

            The products will
            <strong>NOT</strong>
            be deleted.

            They will be moved to the category you select below.

        </div>


        <?php if (!$replacementCategories): ?>


            <div class="danger">

                There is no other active category available.

                <br><br>

                Create another category first, then delete
                <strong>
                    <?= e($category['name']) ?>
                </strong>.

            </div>


            <a href="categories.php">
                Back to Categories
            </a>


        <?php else: ?>


            <form method="POST">
                <?= csrf_field() ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= $categoryId ?>"
                >


                <input
                    type="hidden"
                    name="confirm_delete"
                    value="1"
                >


                <label>
                    Move these products to:
                </label>


                <select
                    name="replacement_category_id"
                    required
                >

                    <option value="">
                        — Select Category —
                    </option>


                    <?php foreach (
                        $replacementCategories
                        as $replacement
                    ): ?>

                        <option
                            value="<?= (int)$replacement['id'] ?>"
                        >

                            <?= e($replacement['name']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <div class="actions">


                    <button type="submit">

                        Move Products &amp;
                        Delete Category

                    </button>


                    <a href="categories.php">

                        Cancel

                    </a>


                </div>


            </form>


        <?php endif; ?>


    <?php else: ?>


        <div class="warning">

            You are about to permanently delete:

            <strong>
                <?= e($category['name']) ?>
            </strong>

            <br><br>

            This category contains no products.

        </div>


        <form method="POST">

        <?= csrf_field() ?>


            <input
                type="hidden"
                name="id"
                value="<?= $categoryId ?>"
            >


            <input
                type="hidden"
                name="confirm_delete"
                value="1"
            >


            <div class="actions">


                <button type="submit">

                    Delete Category

                </button>


                <a href="categories.php">

                    Cancel

                </a>


            </div>


        </form>


    <?php endif; ?>


</div>


</body>

</html>