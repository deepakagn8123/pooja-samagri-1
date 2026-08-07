<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/product-card.php';

$q = trim($_GET['q'] ?? '');

$products = searchProducts($pdo, $q);

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$pageTitle = "Search";

ob_start();
?>

<script>

function quickAdd(slug)
{
    addToCart(slug, 1);

    const toast = document.getElementById("added-toast");

    if (!toast) return;

    toast.classList.add("show");

    setTimeout(function () {
        toast.classList.remove("show");
    }, 1800);
}

</script>

<?php

$pageScripts = ob_get_clean();

require __DIR__ . '/includes/header.php';

?>

<div class="page-banner">

    <span class="eyebrow">
        Search
    </span>

    <h1>
        Search Results
    </h1>

    <?php if ($q !== ''): ?>

        <p>

            Showing results for

            <strong>"<?= e($q) ?>"</strong>

        </p>

    <?php endif; ?>

</div>


<section class="block">

<?php if ($q === ''): ?>

    <div class="empty-state">

        <h2>Start searching...</h2>

    </div>

<?php elseif (empty($products)): ?>

    <div class="empty-state">

        <h2>No products found.</h2>

        <p>Try another keyword.</p>

    </div>

<?php else: ?>

    <div class="prod-grid">

        <?php foreach ($products as $product): ?>

            <?php renderProductCard($product); ?>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

</section>


<div
    class="added-toast"
    id="added-toast"
>
    Cart mein add ho gaya ✓
</div>

<?php

require __DIR__ . '/includes/footer.php';