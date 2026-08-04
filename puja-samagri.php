<?php

require_once __DIR__ . '/config/app.php';

$pujaKits = getProductsByCategory($pdo, 'puja-samagri');
$metalProducts = getProductsByCategory($pdo, 'idols-metal');
$freshProducts = getProductsByCategory($pdo, 'fresh-flowers');

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

?>

<!DOCTYPE html>
<html lang="hi">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Puja Samagri &amp; Kits — ShubhSamagri</title>

<link rel="stylesheet" href="style.css">

</head>

<body>


<header>

    <div class="logo">

        <svg class="logo-mark" viewBox="0 0 40 40" fill="none">
            <ellipse cx="20" cy="27" rx="15" ry="7" fill="#B8860B"/>
            <path d="M20 26c-3 0-6-2-6-5 0-4 3-8 6-14 3 6 6 10 6 14 0 3-3 5-6 5z" fill="#E8890C"/>
            <path d="M20 21c-1.4 0-2.6-1-2.6-2.3 0-1.7 1.3-3.5 2.6-6 1.3 2.5 2.6 4.3 2.6 6 0 1.3-1.2 2.3-2.6 2.3z" fill="#FBD599"/>
        </svg>

        <div class="logo-text">
            Shubh<span>Samagri</span>
        </div>

    </div>


    <nav>

        <ul>
            <li><a href="index.php">Home</a></li>

            <li>
                <a href="puja-samagri.php" class="active">
                    Puja Samagri
                </a>
            </li>

            <li><a href="wedding-items.php">Wedding Items</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>

    </nav>


    <div class="nav-actions">

        <a href="cart.php" class="cart-link" aria-label="Cart">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>

                <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/>

            </svg>

            <span
                class="cart-badge"
                id="cart-count"
                style="display:none;"
            >
                0
            </span>

        </a>


        <a href="contact.php" class="nav-cta">

            <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="currentColor"
            >
                <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
            </svg>

            WhatsApp Order

        </a>

    </div>


    <button
        class="menu-btn"
        id="menuBtn"
        aria-label="Open menu"
        aria-expanded="false"
        type="button"
    >
        ☰
    </button>

</header>


<div class="garland">

    <svg viewBox="0 0 1200 34" preserveAspectRatio="none">

        <path
            d="M0 4 Q60 30 120 4 T240 4 T360 4 T480 4 T600 4 T720 4 T840 4 T960 4 T1080 4 T1200 4"
            stroke="#5C7A4A"
            stroke-width="2"
            fill="none"
        />

        <g fill="#E8890C">

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
        Puja Samagri
    </span>

    <h1>
        Roz ki puja se anushthan tak
    </h1>

    <p>
        Shuddh aur taaza samagri — ready-made kits se lekar
        individual items tak, sab kuch ek jagah.
    </p>

</div>


<!-- PUJA KITS -->

<section class="block">

    <div class="section-head">

        <span class="eyebrow">
            Best sellers
        </span>

        <h2>
            Ready-made puja kits
        </h2>

    </div>


    <div class="prod-grid">

        <?php if ($pujaKits): ?>

            <?php foreach ($pujaKits as $product): ?>

                <?php renderProductCard($product); ?>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No products available.</p>

        <?php endif; ?>

    </div>

</section>


<!-- IDOLS AND METAL -->

<section class="block" style="padding-top:0;">

    <div class="section-head">

        <span class="eyebrow">
            Idols &amp; metal articles
        </span>

        <h2>
            Peetal aur tamba ke bartan
        </h2>

    </div>


    <div class="prod-grid">

        <?php if ($metalProducts): ?>

            <?php foreach ($metalProducts as $product): ?>

                <?php renderProductCard($product); ?>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No products available.</p>

        <?php endif; ?>

    </div>

</section>


<!-- FRESH PRODUCTS -->

<section class="block" style="padding-top:0;">

    <div class="section-head">

        <span class="eyebrow">
            Fresh daily
        </span>

        <h2>
            Taaza puja phool
        </h2>

        <p>
            Genda mala, tulsi patta, paan — daily subscription
            (abhi sirf aapke shehar mein available).
        </p>

    </div>


    <div class="prod-grid">

        <?php if ($freshProducts): ?>

            <?php foreach ($freshProducts as $product): ?>

                <?php renderProductCard($product); ?>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No products available.</p>

        <?php endif; ?>

    </div>

</section>


<section class="block">

    <div class="wa-feature">

        <div>

            <h2>
                List lambi hai? Bas WhatsApp karein
            </h2>

            <p>
                Samagri ki poori list photo ya text mein bhejein,
                hum arrange karke deliver kar denge.
            </p>

            <a href="contact.php" class="wa-btn">

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
                </svg>

                List bhejein

            </a>

        </div>


        <div class="wa-mock">

            <div class="wa-bubble">
                Upanayan sanskar ke liye samagri list bhej raha hoon
            </div>

            <div class="wa-bubble me">
                Mil gaya! Kal shaam tak ready ho jayega,
                PAN-India shipping bhi available hai 🙏
            </div>

        </div>

    </div>

</section>


<footer>

    <div class="foot-grid">

        <div>

            <div
                class="logo-text"
                style="margin-bottom:12px;"
            >
                Shubh<span style="color:#FBD599;">Samagri</span>
            </div>

            <p style="max-width:260px;">
                Puja samagri aur wedding items — local shehar mein
                same-day, poore Bharat mein shipping ke saath.
            </p>

        </div>


        <div>

            <h4>Categories</h4>

            <a href="puja-samagri.php">
                Puja samagri
            </a>

            <a href="wedding-items.php">
                Wedding items
            </a>

            <a href="services.php">
                Services
            </a>

        </div>


        <div>

            <h4>Company</h4>

            <a href="index.php">
                Home
            </a>

            <a href="contact.php">
                Contact
            </a>

        </div>


        <div>

            <h4>Contact</h4>

            <a href="#">
                WhatsApp: +91 00000 00000
            </a>

            <a href="#">
                hello@shubhsamagri.com
            </a>

            <a href="#">
                Local city + Pan-India
            </a>

        </div>

    </div>


    <div class="foot-bottom">

        <span>
            © 2026 ShubhSamagri. Demo mockup — sample business hai.
        </span>

        <span>
            Made for aapka business
        </span>

    </div>

</footer>


<div
    class="added-toast"
    id="added-toast"
>
    Cart mein add ho gaya ✓
</div>


<script src="cart.js"></script>

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


</body>
</html>