<?php

require_once __DIR__ . '/config/app.php';


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
| Get slug
|--------------------------------------------------------------------------
*/

$slug = request_string(
    $_GET['slug'] ?? ''
);

$slug = mb_substr($slug, 0, 120);

if (!valid_slug($slug, 120)) {
    http_response_code(400);
    exit('Invalid category.');
}


if ($slug === '') {

    header('Location: categories.php');

    exit;

}


/*
|--------------------------------------------------------------------------
| Get category
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        name,
        slug,
        image,
        description

    FROM categories

    WHERE slug = :slug
      AND is_active = 1

    LIMIT 1
");

$stmt->execute([
    'slug' => $slug
]);


$currentCategory = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$currentCategory) {

    http_response_code(404);

    $pageTitle =
        'Category Not Found — Nitya Ritual E-Store';

    require __DIR__ . '/includes/header.php';

    ?>

    <main class="categories-page">

        <div class="page-banner">

            <span class="eyebrow">
                404
            </span>

            <h1>
                Category nahi mili
            </h1>

            <p>
                Yeh category available nahi hai.
            </p>

            <a
                href="categories.php"
                class="btn-primary"
                style="
                    display:inline-block;
                    margin-top:20px;
                    text-decoration:none;
                "
            >
                Browse Categories
            </a>

        </div>

    </main>

    <?php

    require __DIR__ . '/includes/footer.php';

    exit;

}

/*
|--------------------------------------------------------------------------
| Get products directly inside this category
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        name,
        slug,
        description,
        price,
        old_price,
        image,
        badge,
        tag,
        unit

    FROM products

    WHERE category_id = :category_id
      AND is_active = 1

    ORDER BY
        id DESC
");

$stmt->execute([
    'category_id' => $currentCategory['id']
]);
$products = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Product card
|--------------------------------------------------------------------------
*/

function renderCategoryProductCard(
    array $product
): void {

?>

    <div class="prod-card">


        <a
            href="product.php?slug=<?= urlencode($product['slug']) ?>"
            class="prod-link"
        >


            <div class="prod-visual">


                <?php if (!empty($product['badge'])): ?>

                    <span class="sale-badge">
                        <?= e($product['badge']) ?>
                    </span>

                <?php endif; ?>


                <?php if (!empty($product['image'])): ?>

                    <img
                        src="assets/images/products/<?= rawurlencode($product['image']) ?>"
                        alt="<?= e($product['name']) ?>"
                        class="product-card-image"
                        loading="lazy"
                    >

                <?php else: ?>

                    <div class="product-image-placeholder">
                        Image coming soon
                    </div>

                <?php endif; ?>


            </div>


            <div class="prod-body">


                <h4>
                    <?= e($product['name']) ?>
                </h4>


                <div class="price">


                    <?php if (!empty($product['old_price'])): ?>

                        <s>
                            ₹<?= number_format(
                                (float)$product['old_price'],
                                0
                            ) ?>
                        </s>

                    <?php endif; ?>


                    ₹<?= number_format(
                        (float)$product['price'],
                        0
                    ) ?>


                    <?= e(
                        $product['unit'] ?? ''
                    ) ?>


                </div>


                <?php if (!empty($product['tag'])): ?>

                    <span class="tag">

                        <?= e($product['tag']) ?>

                    </span>

                <?php endif; ?>


            </div>


        </a>


        <button
    type="button"
    class="quick-add"
    onclick="quickAdd('<?= e($product['slug']) ?>')"
    aria-label="Add to cart"
>
    +
</button>


    </div>

<?php

}


/*
|--------------------------------------------------------------------------
| Page JavaScript
|--------------------------------------------------------------------------
*/

ob_start();

?>

<script>

function quickAdd(id)
{
    addToCart(id, 1);

    const toast =
        document.getElementById('added-toast');

    if (!toast) {
        return;
    }

    toast.classList.add('show');

    setTimeout(() => {

        toast.classList.remove('show');

    }, 1800);
}

</script>

<?php

$pageScripts =
    ob_get_clean();


$pageTitle =
    $currentCategory['name'] .
    ' — Nitya Ritual E-Store';


require __DIR__ . '/includes/header.php';

?>


<main class="category-page">


    <div class="garland">

        <svg
            viewBox="0 0 1200 34"
            preserveAspectRatio="none"
        >

            <path
                d="M0 4 Q60 30 120 4 T240 4 T360 4 T480 4 T600 4 T720 4 T840 4 T960 4 T1080 4 T1200 4"
                stroke="#5C7A4A"
                stroke-width="2"
                fill="none"
            />

            <g fill="#E8890C">

                <?php

                for ($x = 20; $x <= 1180; $x += 40):

                ?>

                    <circle
                        cx="<?= $x ?>"
                        cy="<?= ($x / 40) % 2 === 0 ? 14 : 22 ?>"
                        r="6"
                    />

                <?php endfor; ?>

            </g>

        </svg>

    </div>

<div class="page-banner">

    <span class="eyebrow">
        Category
    </span>

<h1>
    <?= e($currentCategory['name']) ?>
</h1>

<?php if (!empty($currentCategory['description'])): ?>

    <p>
        <?= e($currentCategory['description']) ?>
    </p>

<?php endif; ?>

</div>

    <?php if ($products): ?>


        <section class="block">


            <div class="prod-grid">


                <?php foreach ($products as $product): ?>

                    <?php
                    renderCategoryProductCard(
                        $product
                    );
                    ?>

                <?php endforeach; ?>


            </div>


        </section>


    <?php else: ?>


        <section class="block">

            <p>
                Is category mein abhi products available nahi hain.
            </p>

        </section>


    <?php endif; ?>


</main>


<div
    class="added-toast"
    id="added-toast"
>
    Cart mein add ho gaya ✓
</div>


<?php

require __DIR__ . '/includes/footer.php';

?>