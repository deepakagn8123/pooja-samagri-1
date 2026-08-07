<?php

require_once __DIR__.'/config/app.php';

$slug=$_GET['slug'] ?? '';

$category=getCategoryBySlug($pdo,$slug);

if(!$category){

    http_response_code(404);

    exit('Category not found.');

}

$products=getProductsByCategory($pdo,$slug);

function e($value):string{

    return htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8');

}

$pageTitle=$category['name'];

require __DIR__.'/includes/header.php';
?>

<div class="page-banner">

    <span class="eyebrow">

        Category

    </span>

    <h1>

        <?= e($category['name']) ?>

    </h1>

</div>

<section class="block">

    <div class="prod-grid">

        <?php foreach($products as $product): ?>

            <?php renderProductCard($product); ?>

        <?php endforeach; ?>

    </div>

</section>

<?php require __DIR__.'/includes/footer.php'; ?>