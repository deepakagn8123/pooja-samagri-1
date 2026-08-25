<?php

if (!function_exists('renderProductCard')) {

function renderProductCard(array $product): void
{
?>

<div class="prod-card">

    <a
        href="product.php?slug=<?= urlencode($product['slug']) ?>"
        class="prod-link"
    >

        <div class="prod-visual">

            <?php if (!empty($product['badge'])): ?>

                <span class="sale-badge">
                    <?= htmlspecialchars($product['badge'], ENT_QUOTES, 'UTF-8') ?>
                </span>

            <?php endif; ?>


            <?php if (!empty($product['image'])): ?>

                <img
                    src="assets/images/products/<?= rawurlencode($product['image']) ?>"
                    alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>"
                    class="product-card-image"
                >

            <?php else: ?>

                <div class="product-image-placeholder">
                    Image Coming Soon
                </div>

            <?php endif; ?>

        </div>


        <div class="prod-body">

            <h4>

                <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>

            </h4>


            <div class="price">

                <?php if (!empty($product['old_price'])): ?>

                    <s>
                        ₹<?= number_format((float)$product['old_price']) ?>
                    </s>

                <?php endif; ?>


                ₹<?= number_format((float)$product['price']) ?>

                <?= htmlspecialchars($product['unit'] ?? '', ENT_QUOTES, 'UTF-8') ?>

            </div>


            <?php if (!empty($product['tag'])): ?>

                <span class="tag">

                    <?= htmlspecialchars($product['tag'], ENT_QUOTES, 'UTF-8') ?>

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

}