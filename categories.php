<?php

require_once __DIR__ . '/config/app.php';

$categories = getAllCategories($pdo);

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$pageTitle = 'All Categories';

require __DIR__ . '/includes/header.php';

?>

<div class="page-banner">

    <span class="eyebrow">
        Browse
    </span>

    <h1>
        All Categories
    </h1>

    <p>
        Explore our complete range of Puja & Wedding essentials.
    </p>

</div>


<section class="block">

    <div class="category-grid">

        <?php foreach($categories as $category): ?>

            <a
                class="category-card"
                href="category.php?slug=<?= urlencode($category['slug']) ?>"
            >

                <div class="category-icon">

                    🪔

                </div>

                <h3>

                    <?= e($category['name']) ?>

                </h3>

                <span>

                    View Products →

                </span>

            </a>

        <?php endforeach; ?>

    </div>

</section>

<?php

require __DIR__.'/includes/footer.php';