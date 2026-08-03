<?php

require_once __DIR__ . '/config/app.php';

$slug = $_GET['slug'] ?? '';

$product = $slug !== ''
    ? getProductBySlug($pdo, $slug)
    : null;

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?= $product ? e($product['name']) . ' — ShubhSamagri' : 'Product — ShubhSamagri' ?>
</title>

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

    <div class="logo-text">Shubh<span>Samagri</span></div>
  </div>

  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="puja-samagri.php">Puja Samagri</a></li>
      <li><a href="wedding-items.php">Wedding Items</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>

  <div class="nav-actions">

    <a href="cart.php" class="cart-link" aria-label="Cart">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/>
      </svg>

      <span class="cart-badge" id="cart-count" style="display:none;">0</span>
    </a>

    <a href="contact.php" class="nav-cta">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
      </svg>
      WhatsApp Order
    </a>

  </div>

  <button class="menu-btn">&#9776;</button>
</header>


<div class="breadcrumb">

  <a href="index.php">Home</a>

  /

  <?php if ($product): ?>

      <span><?= e($product['category_name']) ?></span>

      /

      <span><?= e($product['name']) ?></span>

  <?php else: ?>

      <span>Product</span>

  <?php endif; ?>

</div>


<?php if (!$product): ?>

<div class="product-detail">

    <div style="grid-column:1/-1;text-align:center;padding:60px 0;">

        <h2>Product nahi mila</h2>

        <p style="color:var(--ink-soft);margin-top:10px;">
            <a href="index.php"
               style="color:var(--maroon);font-weight:600;">
                Home par wapas jaayein
            </a>
        </p>

    </div>

</div>

<?php else: ?>


<div class="product-detail">

    <!-- Visual remains temporary until DB images are connected -->

    <div class="pd-visual">

        <?php if (!empty($product['badge'])): ?>

            <span class="pd-badge">
                <?= e($product['badge']) ?>
            </span>

        <?php endif; ?>

        <svg viewBox="0 0 48 48" fill="none">
            <circle cx="24"
                    cy="24"
                    r="14"
                    fill="#B8860B"/>
        </svg>

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


        <p class="pd-desc">
            <?= e($product['description']) ?>
        </p>


        <?php if (!empty($product['tag'])): ?>

            <span class="tag">
                <?= e($product['tag']) ?>
            </span>

        <?php endif; ?>


        <div class="pd-actions">

            <div class="qty-stepper">

                <button onclick="changeQty(-1)"
                        aria-label="Kam karein">
                    −
                </button>

                <span id="qty-display">1</span>

                <button onclick="changeQty(1)"
                        aria-label="Zyada karein">
                    +
                </button>

            </div>


            <button
                class="btn-primary"
                style="border:none;cursor:pointer;"
                onclick="addToCartFromDetail('<?= e($product['slug']) ?>')">

                Cart mein add karein

            </button>


            <a href="#"
               class="wa-btn"
               onclick="buyOnWhatsApp(); return false;">

                <svg width="16"
                     height="16"
                     viewBox="0 0 24 24"
                     fill="currentColor">

                    <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>

                </svg>

                WhatsApp par order karein

            </a>

        </div>

    </div>

</div>

<?php endif; ?>


<div class="related-section">

  <h3>Aapko yeh bhi pasand aayega</h3>

  <div class="prod-grid" id="related-grid"></div>

</div>


<footer>

  <div class="foot-grid">

    <div>

      <div class="logo-text" style="margin-bottom:12px;">
          Shubh<span style="color:#FBD599;">Samagri</span>
      </div>

      <p style="max-width:260px;">
        Puja samagri aur wedding items — local shehar mein
        same-day, poore Bharat mein shipping ke saath.
      </p>

    </div>


    <div>

      <h4>Categories</h4>

      <a href="puja-samagri.php">Puja samagri</a>
      <a href="wedding-items.php">Wedding items</a>
      <a href="services.php">Services</a>

    </div>


    <div>

      <h4>Company</h4>

      <a href="index.php">Home</a>
      <a href="contact.php">Contact</a>

    </div>


    <div>

      <h4>Contact</h4>

      <a href="#">WhatsApp: +91 00000 00000</a>
      <a href="#">hello@shubhsamagri.com</a>
      <a href="#">Local city + Pan-India</a>

    </div>

  </div>


  <div class="foot-bottom">

    <span>
        © 2026 ShubhSamagri. Demo mockup — sample business hai.
    </span>

    <span>Made for aapka business</span>

  </div>

</footer>


<div class="added-toast" id="added-toast">
    Cart mein add ho gaya ✓
</div>


<script src="products.js"></script>
<script src="cart.js"></script>

<script>

let currentQty = 1;


function changeQty(delta) {

    currentQty = Math.max(1, currentQty + delta);

    document.getElementById("qty-display").textContent = currentQty;
}


function addToCartFromDetail(id) {

    addToCart(id, currentQty);

    showToast();
}


function showToast() {

    const toast = document.getElementById('added-toast');

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 1800);
}


function buyOnWhatsApp() {

    <?php if ($product): ?>

    const name = <?= json_encode($product['name']) ?>;
    const price = <?= json_encode((float)$product['price']) ?>;

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

</body>
</html>