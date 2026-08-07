<?php

require_once __DIR__ . '/config/app.php';

$pageTitle = 'ShubhSamagri — Puja Samagri & Wedding Items';


/* =========================================================
   HOMEPAGE DATA
========================================================= */

$homeProducts = [];

try {

    $stmt = $pdo->query("
        SELECT
            p.*,
            c.name AS category_name,
            c.slug AS category_slug
        FROM products p
        INNER JOIN categories c
            ON c.id = p.category_id
        WHERE p.is_active = 1
        ORDER BY p.id DESC
        LIMIT 8
    ");

    $homeProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {

    $homeProducts = [];

}


function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}


function productImage(array $product): string
{
    if (empty($product['image'])) {
        return '';
    }

    return 'assets/images/products/' . rawurlencode($product['image']);
}


ob_start();
?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("menuBtn");
    const mainNav = document.getElementById("mainNav");

    if (menuBtn && mainNav) {

        menuBtn.addEventListener("click", function () {

            mainNav.classList.toggle("active");

            const isOpen = mainNav.classList.contains("active");

            menuBtn.setAttribute("aria-expanded", isOpen);
            menuBtn.innerHTML = isOpen ? "✕" : "☰";

        });

    }

});
</script>

<?php

$pageScripts = ob_get_clean();

require __DIR__ . '/includes/header.php';

?>


<!-- ========================================================
     DECORATIVE GARLAND
======================================================== -->

<div class="garland">

    <svg viewBox="0 0 1200 34" preserveAspectRatio="none">

        <path
            d="M0 4 Q60 30 120 4 T240 4 T360 4 T480 4 T600 4 T720 4 T840 4 T960 4 T1080 4 T1200 4"
            stroke="#5C7A4A"
            stroke-width="2"
            fill="none"
        />

        <g fill="#E8890C">

            <?php for ($x = 20; $x <= 1180; $x += 40): ?>

                <circle
                    cx="<?= $x ?>"
                    cy="<?= (($x / 40) % 2 === 0) ? 22 : 14 ?>"
                    r="6"
                />

            <?php endfor; ?>

        </g>

    </svg>

</div>



<!-- ========================================================
     SHOP BY CATEGORY
======================================================== -->

<section class="home-shop-section">

    <div class="home-section-title">

        <h2>Shop by Category</h2>

        <a href="puja-samagri.php" class="home-see-all">
            See All →
        </a>

    </div>


    <div class="home-category-grid">


        <a href="puja-samagri.php" class="home-category-card">

            <div class="home-category-image">

                <svg viewBox="0 0 120 120">

                    <circle cx="60" cy="60" r="58" fill="#F8E8D0"/>

                    <rect
                        x="30"
                        y="55"
                        width="60"
                        height="38"
                        rx="6"
                        fill="#8B1E3F"
                    />

                    <circle
                        cx="60"
                        cy="40"
                        r="18"
                        fill="#E8890C"
                    />

                    <path
                        d="M52 42c0-13 8-23 8-23s8 10 8 23"
                        fill="#FBD599"
                    />

                </svg>

            </div>

            <h3>
                Daily Puja Items &amp; Puja Kits
            </h3>

        </a>



        <a href="wedding-items.php" class="home-category-card">

            <div class="home-category-image">

                <svg viewBox="0 0 120 120">

                    <circle cx="60" cy="60" r="58" fill="#F6DFD8"/>

                    <path
                        d="M60 20
                           C45 35 37 51 37 68
                           C37 84 47 94 60 94
                           C73 94 83 84 83 68
                           C83 51 75 35 60 20Z"
                        fill="#8B1E3F"
                    />

                    <circle
                        cx="60"
                        cy="70"
                        r="16"
                        fill="#E8890C"
                    />

                </svg>

            </div>

            <h3>
                Wedding Collections
            </h3>

        </a>



        <a href="puja-samagri.php#flowers" class="home-category-card">

            <div class="home-category-image">

                <svg viewBox="0 0 120 120">

                    <circle cx="60" cy="60" r="58" fill="#F8E8D0"/>

                    <circle cx="60" cy="60" r="13" fill="#B8860B"/>

                    <circle cx="60" cy="35" r="15" fill="#E8890C"/>
                    <circle cx="85" cy="60" r="15" fill="#E8890C"/>
                    <circle cx="60" cy="85" r="15" fill="#E8890C"/>
                    <circle cx="35" cy="60" r="15" fill="#E8890C"/>

                </svg>

            </div>

            <h3>
                Daily Fresh Puja Flowers
            </h3>

        </a>



        <a href="services.php" class="home-category-card">

            <div class="home-category-image">

                <svg viewBox="0 0 120 120">

                    <circle cx="60" cy="60" r="58" fill="#F6DFD8"/>

                    <circle
                        cx="60"
                        cy="42"
                        r="18"
                        fill="#B8860B"
                    />

                    <path
                        d="M30 95
                           C32 72 43 61 60 61
                           C77 61 88 72 90 95Z"
                        fill="#8B1E3F"
                    />

                </svg>

            </div>

            <h3>
                Puja &amp; Ritual Services
            </h3>

        </a>



        <a href="puja-samagri.php" class="home-category-card">

            <div class="home-category-image">

                <svg viewBox="0 0 120 120">

                    <circle cx="60" cy="60" r="58" fill="#F8E8D0"/>

                    <rect
                        x="40"
                        y="25"
                        width="40"
                        height="70"
                        rx="6"
                        fill="#B8860B"
                    />

                    <rect
                        x="47"
                        y="38"
                        width="26"
                        height="42"
                        rx="3"
                        fill="#FBD599"
                    />

                    <circle
                        cx="60"
                        cy="56"
                        r="10"
                        fill="#8B1E3F"
                    />

                </svg>

            </div>

            <h3>
                God Idols &amp; Metal Articles
            </h3>

        </a>



        <a href="services.php" class="home-category-card">

            <div class="home-category-image">

                <svg viewBox="0 0 120 120">

                    <circle cx="60" cy="60" r="58" fill="#F6DFD8"/>

                    <circle
                        cx="60"
                        cy="42"
                        r="17"
                        fill="#8B1E3F"
                    />

                    <path
                        d="M30 95
                           C33 72 44 62 60 62
                           C76 62 87 72 90 95Z"
                        fill="#E8890C"
                    />

                </svg>

            </div>

            <h3>
                Book Pandit Ji
            </h3>

        </a>


    </div>

</section>



<!-- ========================================================
     PUJA ORGANIZER BANNER
======================================================== -->

<section class="home-organizer">

    <div class="home-organizer-inner">

        <div>

            <span class="eyebrow">
                Be a guest at your own Puja
            </span>

            <h2>
                Complete Puja and Wedding Package
            </h2>

            <p>
                Samagri se lekar Pandit Ji aur poori puja arrangement
                tak — sab kuch hum sambhaal lenge.
            </p>

            <a href="services.php" class="btn-primary">
                Explore Services
            </a>

        </div>


        <div class="home-organizer-art">

            <svg viewBox="0 0 300 180">

                <circle
                    cx="150"
                    cy="70"
                    r="48"
                    fill="#FBD599"
                />

                <circle
                    cx="150"
                    cy="70"
                    r="32"
                    fill="#E8890C"
                />

                <rect
                    x="75"
                    y="110"
                    width="150"
                    height="45"
                    rx="8"
                    fill="#B8860B"
                />

                <path
                    d="M138 35
                       C138 15 150 5 150 5
                       C150 5 162 15 162 35
                       C162 43 157 48 150 48
                       C143 48 138 43 138 35Z"
                    fill="#8B1E3F"
                />

            </svg>

        </div>

    </div>

</section>

<!-- ========================================================
     WE ARE ALSO AVAILABLE AT
======================================================== -->

<section class="home-available">

    <div class="home-available-heading">
        <h2>We are also available at</h2>
    </div>

    <div class="home-marketplace-grid">

        <!-- AMAZON -->
        <a href="#" class="home-marketplace-item">
            <div class="marketplace-logo amazon-logo">
                <span class="amazon-a">a</span>
                <span class="amazon-smile"></span>
            </div>
            <span class="marketplace-status">Live</span>
        </a>


        <!-- INSTAMART -->
        <a href="#" class="home-marketplace-item">

            <div class="marketplace-square instamart-box">
                <span class="instamart-small">SWIGGY</span>
                <strong>Instamart</strong>
            </div>

            <span class="marketplace-status">Live Soon</span>

        </a>


        <!-- ZEPTO -->
        <a href="#" class="home-marketplace-item">

            <div class="marketplace-square zepto-box">
                <strong>zepto</strong>
            </div>

            <span class="marketplace-status">Live Soon</span>

        </a>


        <!-- BLINKIT -->
        <a href="#" class="home-marketplace-item">

            <div class="marketplace-square blinkit-box">
                <strong>blink<span>it</span></strong>
            </div>

            <span class="marketplace-status">Live Soon</span>

        </a>


        <!-- FLIPKART -->
        <a href="#" class="home-marketplace-item">

            <div class="marketplace-square flipkart-box">
                <strong>f</strong>
            </div>

            <span class="marketplace-status">Live</span>

        </a>


        <!-- AMAZON -->
        <a href="#" class="home-marketplace-item">

            <div class="marketplace-logo amazon-logo">
                <span class="amazon-a">a</span>
                <span class="amazon-smile"></span>
            </div>

            <span class="marketplace-status">Live</span>

        </a>

    </div>

</section>

<!-- ========================================================
     OUR SERVICES
======================================================== -->
<!-- ========================================================
     OUR SERVICES
======================================================== -->

<section class="home-services">

    <div class="home-section-top">
        <h2>Our Services</h2>

        <a href="services.php" class="home-see-all">
            See All →
        </a>
    </div>

    <div class="home-services-grid">

        <!-- PUJA ITEMS -->
        <article class="home-service-card">

            <div class="service-card-top">

                <div class="service-card-image">
                    🪔
                </div>

                <span class="service-pill service-pill-popular">
                    Popular
                </span>

            </div>

            <h3>Puja Items</h3>

            <p>
                Samagri, Dashkarma, incense & more.
                Delivered across India and abroad.
            </p>

            <a href="puja-samagri.php"
               class="service-card-btn service-card-btn-filled">
                Shop Now
            </a>

        </article>


        <!-- FLOWER SUBSCRIPTION -->
        <article class="home-service-card">

            <div class="service-card-top">

                <div class="service-card-image">
                    🌸
                </div>

                <span class="service-pill service-pill-live">
                    Daily
                </span>

            </div>

            <h3>Flower Subscription</h3>

            <p>
                Fresh puja flowers everyday at your door.
                Available in selected serviceable areas.
            </p>

            <a href="services.php"
               class="service-card-btn">
                Subscribe
            </a>

        </article>


        <!-- PANDIT BOOKING -->
        <article class="home-service-card">

            <div class="service-card-top">

                <div class="service-card-image">
                    🙏
                </div>

                <span class="service-pill service-pill-live">
                    Live
                </span>

            </div>

            <h3>Pandit Booking</h3>

            <p>
                Experienced Pandit Ji for Puja, Havan,
                Griha Pravesh and other ceremonies.
            </p>

            <a href="services.php"
               class="service-card-btn">
                Book Pandit
            </a>

        </article>


        <!-- PUJA ORGANIZER -->
        <article class="home-service-card">

            <div class="service-card-top">

                <div class="service-card-image">
                    🪷
                </div>

                <span class="service-pill service-pill-live">
                    Premium
                </span>

            </div>

            <h3>Puja Organizer</h3>

            <p>
                Complete end-to-end Puja arrangement.
                Be a guest at your own Puja.
            </p>

            <a href="services.php"
               class="service-card-btn">
                Explore Now
            </a>

        </article>

    </div>

</section>

<!-- ========================================================
     OUR SERVICEABLE AREA
======================================================== -->
<!-- ========================================================
     OUR SERVICEABLE AREA
======================================================== -->

<section class="serviceable-area">

    <div class="serviceable-heading">

        <h2>Our Serviceable Area</h2>

        <p>We do International Shipping also</p>

    </div>


    <div class="serviceable-features">

        <!-- PAN INDIA -->
        <div class="serviceable-feature">

            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M3 6h11v10H3z"/>
                <path d="M14 10h4l3 3v3h-7z"/>
                <circle cx="7" cy="18" r="2"/>
                <circle cx="18" cy="18" r="2"/>

            </svg>

            <span>PAN India &amp; Abroad</span>

        </div>


        <!-- QUALITY -->
        <div class="serviceable-feature">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6z"/>

                <path d="M9 12l2 2 4-5"/>

            </svg>

            <span>Quality Assured</span>

        </div>


        <!-- SUPPORT -->
        <div class="serviceable-feature">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <circle cx="12" cy="12" r="9"/>

                <path d="M12 7v5l3 2"/>

            </svg>

            <span>24 x 7 Support</span>

        </div>


        <!-- SANATAN -->
        <div class="serviceable-feature">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M8 21v-5c0-2 1-3 2-4"/>
                <path d="M16 21v-5c0-2-1-3-2-4"/>
                <path d="M10 12V6"/>
                <path d="M14 12V6"/>
                <path d="M10 6l2-3 2 3"/>
                <path d="M6 21h12"/>

            </svg>

            <span>For all Sanatanis</span>

        </div>

    </div>

</section>



<!-- ========================================================
     BEST SELLING PRODUCTS
======================================================== -->

<section class="home-products-section">

    <div class="home-section-title">

        <div>

            <span class="eyebrow">
                Popular Products
            </span>

            <h2>
                Best Selling Puja Items
            </h2>

        </div>

        <a href="puja-samagri.php" class="home-see-all">
            View All →
        </a>

    </div>


    <?php if (!empty($homeProducts)): ?>

        <div class="home-product-grid">


            <?php foreach ($homeProducts as $product): ?>

                <?php
                $image = productImage($product);
                ?>


                <article class="home-product-card">


                    <a
                        href="product.php?slug=<?= rawurlencode($product['slug']) ?>"
                        class="home-product-image"
                    >


                        <?php if (!empty($product['badge'])): ?>

                            <span class="home-product-badge">
                                <?= e($product['badge']) ?>
                            </span>

                        <?php endif; ?>


                        <?php if ($image !== ''): ?>

                            <img
                                src="<?= e($image) ?>"
                                alt="<?= e($product['name']) ?>"
                                loading="lazy"
                            >

                        <?php else: ?>

                            <div class="home-product-placeholder">

                                <svg viewBox="0 0 100 100">

                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="32"
                                        fill="#FBD599"
                                    />

                                    <path
                                        d="M40 65
                                           C40 42 50 25 50 25
                                           C50 25 60 42 60 65Z"
                                        fill="#E8890C"
                                    />

                                </svg>

                            </div>

                        <?php endif; ?>


                    </a>


                    <div class="home-product-info">


                        <a
                            href="product.php?slug=<?= rawurlencode($product['slug']) ?>"
                            class="home-product-name"
                        >

                            <?= e($product['name']) ?>

                        </a>


                        <div class="home-product-price">


                            <?php if (!empty($product['old_price'])): ?>

                                <s>
                                    ₹<?= number_format((float)$product['old_price'], 0) ?>
                                </s>

                            <?php endif; ?>


                            <strong>
                                ₹<?= number_format((float)$product['price'], 0) ?>
                            </strong>


                            <?php if (!empty($product['unit'])): ?>

                                <small>
                                    <?= e($product['unit']) ?>
                                </small>

                            <?php endif; ?>


                        </div>


                        <button
                            type="button"
                            class="home-product-add"
                            onclick='homeAddToCart(<?= json_encode($product["slug"]) ?>)'
                            aria-label="Add <?= e($product['name']) ?> to cart"
                        >
                            +
                        </button>


                    </div>

                </article>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <div class="home-products-empty">

            <p>
                Products jaldi yahan dikhai denge.
            </p>

            <a href="puja-samagri.php" class="btn-primary">
                Puja Samagri dekhein
            </a>

        </div>


    <?php endif; ?>


</section>



<!-- ========================================================
     FLOWERS
======================================================== -->

<section class="home-flower-section" id="flowers">

    <div class="home-section-title">

        <div>

            <span class="eyebrow">
                Fresh Every Day
            </span>

            <h2>
                Puja Flowers
            </h2>

        </div>

    </div>


    <div class="home-flower-grid">


        <div class="home-flower-card">

            <div class="home-flower-icon">
                🌼
            </div>

            <h3>Genda Mala</h3>

            <p>
                Roz ki puja aur mandir ke liye taaza mala.
            </p>

        </div>


        <div class="home-flower-card">

            <div class="home-flower-icon">
                🌿
            </div>

            <h3>Tulsi Patta</h3>

            <p>
                Daily puja ke liye fresh tulsi.
            </p>

        </div>


        <div class="home-flower-card">

            <div class="home-flower-icon">
                🍃
            </div>

            <h3>Paan Patta</h3>

            <p>
                Puja aur anushthan ke liye selected fresh leaves.
            </p>

        </div>


        <div class="home-flower-card">

            <div class="home-flower-icon">
                🪷
            </div>

            <h3>Custom Flower Order</h3>

            <p>
                Special puja aur functions ke liye advance booking.
            </p>

        </div>


    </div>

</section>



<!-- ========================================================
     SEND PUJA LIST
======================================================== -->

<section class="home-puja-list">

    <div class="home-puja-list-inner">


        <div>

            <span class="eyebrow">
                Fastest way to order
            </span>

            <h2>
                Apni Puja List WhatsApp karein
            </h2>

            <p>
                Har product alag-alag search karne ki zaroorat nahi.
                Apni handwritten list ki photo ya text bhejiye —
                hum availability aur price confirm kar denge.
            </p>


            <a
                href="contact.php"
                class="wa-btn"
            >

                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
                </svg>

                Send Puja List

            </a>

        </div>


        <div class="home-puja-steps">


            <div>

                <b>1</b>

                <span>
                    List ki photo bhejein
                </span>

            </div>


            <div>

                <b>2</b>

                <span>
                    Price confirmation paayein
                </span>

            </div>


            <div>

                <b>3</b>

                <span>
                    Delivery receive karein
                </span>

            </div>


        </div>


    </div>

</section>



<!-- ========================================================
     TRUST
======================================================== -->

<section class="home-trust">

    <div class="home-trust-grid">


        <div>

            <strong>
                Pan-India
            </strong>

            <span>
                Shipping
            </span>

        </div>


        <div>

            <strong>
                Same Day
            </strong>

            <span>
                Local Delivery
            </span>

        </div>


        <div>

            <strong>
                Shuddh
            </strong>

            <span>
                Puja Samagri
            </span>

        </div>


        <div>

            <strong>
                WhatsApp
            </strong>

            <span>
                Easy Ordering
            </span>

        </div>


    </div>

</section>



<!-- ========================================================
     TESTIMONIALS
======================================================== -->
<!-- ========================================================
     CUSTOMER REVIEWS
======================================================== -->

<section class="home-reviews">

    <div class="home-simple-heading">

        <h2>Customer Reviews</h2>

        <p>
            What our customers say about ShubhSamagri
        </p>

    </div>


    <div class="home-review-grid">


        <!-- REVIEW 1 -->
        <div class="home-review-card">

            <div class="home-review-stars">
                ★★★★★
            </div>

            <p class="home-review-text">
                “Puja ke liye saari samagri ek hi jagah mil gayi.
                Packing bahut acchi thi aur delivery bhi time par hui.
                Bahut convenient service hai.”
            </p>

            <div class="home-review-user">

                <div class="home-review-avatar">
                    RS
                </div>

                <div>
                    <strong>Ritu Sharma</strong>
                    <span>Jaipur, Rajasthan</span>
                </div>

            </div>

        </div>


        <!-- REVIEW 2 -->
        <div class="home-review-card">

            <div class="home-review-stars">
                ★★★★★
            </div>

            <p class="home-review-text">
                “Wedding ke liye traditional items dhoondhna difficult
                tha. Yahan Maur aur baaki required samagri easily
                mil gayi. Quality bhi bahut acchi thi.”
            </p>

            <div class="home-review-user">

                <div class="home-review-avatar">
                    AK
                </div>

                <div>
                    <strong>Amit Kumar</strong>
                    <span>Patna, Bihar</span>
                </div>

            </div>

        </div>


        <!-- REVIEW 3 -->
        <div class="home-review-card">

            <div class="home-review-stars">
                ★★★★★
            </div>

            <p class="home-review-text">
                “WhatsApp par puja list bheji aur sab saman arrange
                ho gaya. Har item individually search karne ki
                zaroorat hi nahi padi.”
            </p>

            <div class="home-review-user">

                <div class="home-review-avatar">
                    SD
                </div>

                <div>
                    <strong>Sunita Devi</strong>
                    <span>Kolkata, West Bengal</span>
                </div>

            </div>

        </div>


    </div>


    <div class="home-review-summary">

        <div class="home-review-score">
            <strong>4.9</strong>

            <div>
                <span>★★★★★</span>
                <small>Based on customer reviews</small>
            </div>
        </div>

        <a href="contact.php" class="home-review-action">
            Share Your Experience →
        </a>

    </div>

</section>


<!-- ========================================================
     ADD TO CART TOAST
======================================================== -->

<div
    class="added-toast"
    id="added-toast"
>
    Cart mein add ho gaya ✓
</div>



<script>

function homeAddToCart(slug) {

    if (typeof addToCart !== "function") {
        console.error("cart.js is not loaded.");
        return;
    }

    addToCart(slug, 1);

    const toast = document.getElementById("added-toast");

    if (toast) {

        toast.classList.add("show");

        setTimeout(function () {
            toast.classList.remove("show");
        }, 1600);

    }

}

// Normal Lottie js, loads at every refresh

// document.body.classList.add("loading");

// window.addEventListener("load", function () {

//     setTimeout(function () {

//         document.getElementById("page-loader").classList.add("hide");
//         document.body.classList.remove("loading");

//     }, 5500);   // Adjust if you want the loader to stay slightly longer

// });

// Lottie js for one load per tab
window.addEventListener("load", function () {

    const loader = document.getElementById("page-loader");

    if (!loader) {
        document.body.classList.remove("loading");
        return;
    }

    setTimeout(() => {

        loader.classList.add("hide");

        setTimeout(() => {

            loader.remove();
            document.body.classList.remove("loading");

        }, 800);

    }, 5500);

});

</script>




<?php

require __DIR__ . '/includes/footer.php';

?>