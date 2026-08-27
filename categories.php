<?php

require_once __DIR__ . '/config/app.php';

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
| Active Top-Level Categories
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

        (
            SELECT COUNT(*)
            FROM categories child
            WHERE child.parent_id = c.id
              AND child.is_active = 1
        ) AS subcategory_count,

        (
            SELECT COUNT(*)
            FROM products p
            WHERE p.category_id = c.id
              AND p.is_active = 1
        ) AS product_count

    FROM categories c

    WHERE c.parent_id IS NULL
      AND c.is_active = 1

    ORDER BY
        c.sort_order ASC,
        c.name ASC
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$pageTitle = 'All Categories — Nitya Ritual E-Store';

require __DIR__ . '/includes/header.php';

?>


<div class="page-banner">

    <span class="eyebrow">
        Categories
    </span>

    <h1>
        Browse All Categories
    </h1>

    <p>
        Explore all our puja, wedding and ritual products.
    </p>

</div>


<section class="block">

    <div class="category-grid">


        <?php if ($categories): ?>


            <?php foreach ($categories as $category): ?>


                <a
                    href="category.php?slug=<?= urlencode($category['slug']) ?>"
                    class="category-card"
                >


                    <div class="category-icon">


                        <?php if (!empty($category['image'])): ?>

                            <img
                                src="assets/images/categories/<?= rawurlencode($category['image']) ?>"
                                alt="<?= e($category['name']) ?>"
                                loading="lazy"
                            >

                        <?php else: ?>

                            🪔

                        <?php endif; ?>


                    </div>


                    <h3>

                        <?= e($category['name']) ?>

                    </h3>


                    <?php if (!empty($category['description'])): ?>

                        <p>

                            <?= e($category['description']) ?>

                        </p>

                    <?php endif; ?>


                    <p>

                        <?= (int)$category['product_count'] ?>

                        Products

                    </p>


                </a>


            <?php endforeach; ?>


        <?php else: ?>


            <div class="home-products-empty">

                <p>
                    No categories available.
                </p>

            </div>


        <?php endif; ?>


    </div>

</section>


<?php

require __DIR__ . '/includes/footer.php';

?>