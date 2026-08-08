<?php

require_once __DIR__ . '/config/app.php';

$categories = getAllCategories($pdo);

$pageTitle = "All Categories";

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
        Explore all our puja and wedding products.
    </p>

</div>


<section class="block">

    <div class="category-grid">

        <?php foreach($categories as $category): ?>

            <a
                href="category.php?slug=<?= urlencode($category['slug']) ?>"
                class="category-card"
            >

                <div class="category-icon">

                    🪔

                </div>

                <h3>

                    <?= htmlspecialchars($category['name']) ?>

                </h3>

                <p>

                    <?= $category['total_products'] ?>

                    Products

                </p>

            </a>

        <?php endforeach; ?>

    </div>

</section>

<?php

require __DIR__.'/includes/footer.php';