<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/product-card.php';

$slug = $_GET['slug'] ?? '';

$category = getCategoryBySlug($pdo, $slug);

if (!$category) {
    http_response_code(404);
    exit('Category not found');
}

$products = getProductsByCategory($pdo, $slug);

$pageTitle = $category['name'];

ob_start();
?>

<script>

function quickAdd(slug)
{
    addToCart(slug,1);

    const toast=document.getElementById("added-toast");

    if(!toast) return;

    toast.classList.add("show");

    setTimeout(function(){
        toast.classList.remove("show");
    },1800);
}

</script>

<?php

$pageScripts = ob_get_clean();

require __DIR__ . '/includes/header.php';

?>

<div class="page-banner">

    <span class="eyebrow">

        Category

    </span>

    <h1>

        <?= htmlspecialchars($category['name']) ?>

    </h1>

    <p>

        <?= count($products) ?> Products Available

    </p>

</div>


<section class="block">

<?php if(empty($products)): ?>

    <div class="empty-state">

        <h2>No Products Found</h2>

    </div>

<?php else: ?>

    <div class="prod-grid">

        <?php foreach($products as $product): ?>

            <?php renderProductCard($product); ?>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

</section>


<div class="added-toast" id="added-toast">

Cart mein add ho gaya ✓

</div>

<?php

require __DIR__.'/includes/footer.php';