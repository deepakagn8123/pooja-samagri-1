<?php

require_once __DIR__ . '/config/app.php';

$slug = trim($_GET['slug'] ?? '');

$slug = request_string(
    $_GET['slug'] ?? ''
);

$slug = mb_substr($slug, 0, 180);

$product = valid_slug($slug)
    ? getProductBySlug($pdo, $slug)
    : null;

require_once __DIR__ . '/includes/product-card.php';

$relatedProducts = [];

if ($product) {

    $relatedProducts = getRelatedProducts(
        $pdo,
        (int)$product['category_id'],
        (int)$product['id'],
        4
    );

}

$relatedProducts = [];

if ($product) {
    $relatedProducts = getRelatedProducts(
        $pdo,
        (int)$product['category_id'],
        (int)$product['id'],
        4
    );
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}


/*
|--------------------------------------------------------------------------
| Page Specific JavaScript
|--------------------------------------------------------------------------
*/

ob_start();
?>

<script>

let currentQty = 1;


function changeQty(delta)
{
    currentQty = Math.max(1, currentQty + delta);

    const display = document.getElementById("qty-display");

    if (display) {
        display.textContent = currentQty;
    }
}


function addToCartFromDetail(id)
{
    addToCart(id, currentQty);

    showToast();
}


function quickAdd(id)
{
    addToCart(id, 1);

    showToast();
}


function showToast()
{
    const toast = document.getElementById('added-toast');

    if (!toast) {
        return;
    }

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 1800);
}


function buyOnWhatsApp()
{
    <?php if ($product): ?>

    const name = <?= js_json($product['name']) ?>;
const price = <?= js_json((float)$product['price']) ?>;

    const total = price * currentQty;

    const msg =
        `Namaste! Mujhe yeh order karna hai: ${name} (Qty: ${currentQty}) — ₹${total.toLocaleString("en-IN")}`;

    window.open(
        `https://wa.me/910000000000?text=${encodeURIComponent(msg)}`,
        "_blank"
    );

    <?php endif; ?>
}

</script>

<?php

$pageScripts = ob_get_clean();

$pageTitle = $product
    ? $product['name'] . ' — ShubhSamagri'
    : 'Product — ShubhSamagri';

require __DIR__ . '/includes/header.php';

?>


<div class="breadcrumb">

    <a href="index.php">
        Home
    </a>

    /

    <?php if ($product): ?>

        <span>
            <?= e($product['category_name']) ?>
        </span>

        /

        <span>
            <?= e($product['name']) ?>
        </span>

    <?php else: ?>

        <span>Product</span>

    <?php endif; ?>

</div>


<?php if (!$product): ?>


<div class="product-detail">

    <div
        style="
            grid-column:1/-1;
            text-align:center;
            padding:60px 0;
        "
    >

        <h2>
            Product nahi mila
        </h2>

        <p
            style="
                color:var(--ink-soft);
                margin-top:10px;
            "
        >

            <a
                href="index.php"
                style="
                    color:var(--maroon);
                    font-weight:600;
                "
            >
                Home par wapas jaayein
            </a>

        </p>

    </div>

</div>


<?php else: ?>


<div class="product-detail">


    <div class="pd-visual">

        <?php if (!empty($product['badge'])): ?>

            <span class="pd-badge">
                <?= e($product['badge']) ?>
            </span>

        <?php endif; ?>


        <?php if (!empty($product['image'])): ?>

            <img
                src="assets/images/products/<?= rawurlencode($product['image']) ?>"
                alt="<?= e($product['name']) ?>"
                class="product-main-image"
            >

        <?php else: ?>

            <div class="product-image-placeholder">
                Image coming soon
            </div>

        <?php endif; ?>

    </div>


    <div class="pd-info">

        <span class="pd-tag">
            <?= e($product['category_name']) ?>
        </span>


        <h1>
            <?= e($product['name']) ?>
        </h1>


        <div class="pd-price">

            <?php if (!empty($product['old_price'])): ?>

                <s>
                    ₹<?= number_format((float)$product['old_price'], 0) ?>
                </s>

            <?php endif; ?>


            ₹<?= number_format((float)$product['price'], 0) ?>

            <?= e($product['unit'] ?? '') ?>

        </div>


        <?php if (!empty($product['description'])): ?>

            <p class="pd-desc">
                <?= e($product['description']) ?>
            </p>

        <?php endif; ?>


        <?php if (!empty($product['tag'])): ?>

            <span class="tag">
                <?= e($product['tag']) ?>
            </span>

        <?php endif; ?>


        <div class="pd-actions">

            <div class="qty-stepper">

                <button
                    onclick="changeQty(-1)"
                    aria-label="Kam karein"
                >
                    −
                </button>

                <span id="qty-display">
                    1
                </span>

                <button
                    onclick="changeQty(1)"
                    aria-label="Zyada karein"
                >
                    +
                </button>

            </div>


            <button
    class="btn-primary"
    style="border:none;cursor:pointer;"
    onclick="addToCartFromDetail('<?= e($product['slug']) ?>')"
>
    Cart mein add karein
</button>


            <a
                href="#"
                class="wa-btn"
                onclick="buyOnWhatsApp(); return false;"
            >

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
                </svg>

                WhatsApp par order karein

            </a>

        </div>

    </div>

</div>


<?php if (!empty($relatedProducts)): ?>

<div class="related-section">

    <h3>
        Aapko yeh bhi pasand aayega
    </h3>


    <div class="prod-grid">

<?php foreach($relatedProducts as $related): ?>

    <?php renderProductCard($related); ?>

<?php endforeach; ?>

</div>

</div>

<?php endif; ?>


<?php endif; ?>


<div
    class="added-toast"
    id="added-toast"
>
    Cart mein add ho gaya ✓
</div>


<?php
require __DIR__ . '/includes/footer.php';
?>