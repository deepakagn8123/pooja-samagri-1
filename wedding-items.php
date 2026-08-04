<?php

require_once __DIR__ . '/config/app.php';

$products = getProductsByCategory($pdo, 'wedding-items');

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

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
                        <?= e($product['badge']) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($product['image'])): ?>

                    <img
                        src="assets/images/products/<?= rawurlencode($product['image']) ?>"
                        alt="<?= e($product['name']) ?>"
                        class="product-card-image"
                    >

                <?php else: ?>

                    <div class="product-image-placeholder">
                        Image coming soon
                    </div>

                <?php endif; ?>

            </div>

            <div class="prod-body">

                <h4><?= e($product['name']) ?></h4>

                <div class="price">

                    <?php if (!empty($product['old_price'])): ?>
                        <s>
                            ₹<?= number_format((float)$product['old_price'], 0) ?>
                        </s>
                    <?php endif; ?>

                    ₹<?= number_format((float)$product['price'], 0) ?>

                    <?= e($product['unit'] ?? '') ?>

                </div>

                <?php if (!empty($product['tag'])): ?>
                    <span class="tag">
                        <?= e($product['tag']) ?>
                    </span>
                <?php endif; ?>

            </div>

        </a>

        <button
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
| Page-specific JS
|--------------------------------------------------------------------------
*/

ob_start();
?>

<script>

function quickAdd(id)
{
    addToCart(id, 1);

    const toast = document.getElementById('added-toast');

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 1800);
}

</script>

<?php

$pageScripts = ob_get_clean();

$pageTitle = 'Wedding Items — ShubhSamagri';

require __DIR__ . '/includes/header.php';

?>


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

        <g fill="#8B1E3F">

            <circle cx="20" cy="14" r="6"/>
            <circle cx="60" cy="22" r="6"/>
            <circle cx="100" cy="14" r="6"/>
            <circle cx="140" cy="22" r="6"/>
            <circle cx="180" cy="14" r="6"/>
            <circle cx="220" cy="22" r="6"/>
            <circle cx="260" cy="14" r="6"/>
            <circle cx="300" cy="22" r="6"/>
            <circle cx="340" cy="14" r="6"/>
            <circle cx="380" cy="22" r="6"/>
            <circle cx="420" cy="14" r="6"/>
            <circle cx="460" cy="22" r="6"/>
            <circle cx="500" cy="14" r="6"/>
            <circle cx="540" cy="22" r="6"/>
            <circle cx="580" cy="14" r="6"/>
            <circle cx="620" cy="22" r="6"/>
            <circle cx="660" cy="14" r="6"/>
            <circle cx="700" cy="22" r="6"/>
            <circle cx="740" cy="14" r="6"/>
            <circle cx="780" cy="22" r="6"/>
            <circle cx="820" cy="14" r="6"/>
            <circle cx="860" cy="22" r="6"/>
            <circle cx="900" cy="14" r="6"/>
            <circle cx="940" cy="22" r="6"/>
            <circle cx="980" cy="14" r="6"/>
            <circle cx="1020" cy="22" r="6"/>
            <circle cx="1060" cy="14" r="6"/>
            <circle cx="1100" cy="22" r="6"/>
            <circle cx="1140" cy="14" r="6"/>
            <circle cx="1180" cy="22" r="6"/>

        </g>

    </svg>

</div>


<div class="page-banner">

    <span class="eyebrow">
        Wedding Items
    </span>

    <h1>
        Vivah ki parampara, poori taiyari ke saath
    </h1>

    <p>
        Har samuday ki apni riti — hum har ek ke liye
        authentic saman rakhte hain.
    </p>

</div>


<section class="block">

    <div class="region-tabs">

        <span class="region-tab active">
            Sabhi
        </span>

        <span class="region-tab">
            Bengali
        </span>

        <span class="region-tab">
            Bihari
        </span>

        <span class="region-tab">
            Marwadi
        </span>

    </div>


    <div class="prod-grid">

        <?php if ($products): ?>

            <?php foreach ($products as $product): ?>

                <?php renderProductCard($product); ?>

            <?php endforeach; ?>

        <?php else: ?>

            <p>
                No wedding products available.
            </p>

        <?php endif; ?>

    </div>

</section>


<section class="block">

    <div class="wa-feature">

        <div>

            <h2>
                Wedding ki poori list ek saath
            </h2>

            <p>
                Apni shaadi ki tareekh aur tradition bataiye —
                hum aapke liye ek complete checklist aur quote
                WhatsApp par bhej denge.
            </p>

            <a
                href="contact.php"
                class="wa-btn"
            >

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
                </svg>

                Wedding list bhejein

            </a>

        </div>


        <div class="wa-mock">

            <div class="wa-bubble">
                Bengali wedding hai, February mein —
                kya kya chahiye hoga poora set?
            </div>

            <div class="wa-bubble me">
                Topor-Mukut, Gachkouto, Kalash aur mandap
                flowers ka poora package bhej raha hoon,
                ₹4200 total 🙏
            </div>

        </div>

    </div>

</section>


<div
    class="added-toast"
    id="added-toast"
>
    Cart mein add ho gaya ✓
</div>


<?php
require __DIR__ . '/includes/footer.php';
?>