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

                <img
                    src="assets/images/categories/Pooja_essential.webp"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >           

            </div>

            <h3>
                Daily Puja Items &amp; Puja Kits
            </h3>

        </a>



        <a href="wedding-items.php" class="home-category-card">

            <div class="home-category-image">

                <img
                    src="assets/images/categories/717vdrxwYZL.jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  

            </div>

            <h3>
                Wedding Collections
            </h3>

        </a>



        <a href="puja-samagri.php#flowers" class="home-category-card">

            <div class="home-category-image">

                <img
                    src="assets/images/categories/Untitled-design-79.webp"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  

            </div>

            <h3>
                Daily Fresh Puja Flowers
            </h3>

        </a>



        <a href="services.php" class="home-category-card">

            <div class="home-category-image">

                <img
                    src="assets/images/categories/puja-services.jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  

            </div>

            <h3>
                Puja &amp; Ritual Services
            </h3>

        </a>



        <a href="puja-samagri.php" class="home-category-card">

            <div class="home-category-image">

                <img
                    src="assets/images/categories/51HW4wy1edL._SL500_.jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  

            </div>

            <h3>
                God Idols &amp; Metal Articles
            </h3>

        </a>



        <a href="services.php" class="home-category-card">

            <div class="home-category-image">

                <img
                    src="assets/images/categories/images.jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  

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

            <img
                    src="assets/images/categories/images (1).jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy" 
                > 

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
                    <img
                    src="assets/images/categories/images (2).jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
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
                    <img
                    src="assets/images/categories/mixedflowers-350x350.webp"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
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
                    <img
                    src="assets/images/categories/about_us.jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
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
                    <img
                    src="assets/images/categories/images (1).jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
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
                <img
                    src="assets/images/categories/gardland.png"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
            </div>

            <h3>Genda Mala</h3>

            <p>
                Roz ki puja aur mandir ke liye taaza mala.
            </p>

        </div>


        <div class="home-flower-card">

            <div class="home-flower-icon">
                <img
                    src="assets/images/categories/tulsi.png"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
            </div>

            <h3>Tulsi Patta</h3>

            <p>
                Daily puja ke liye fresh tulsi.
            </p>

        </div>


        <div class="home-flower-card">

            <div class="home-flower-icon">
                <img
                    src="assets/images/categories/paan.webp"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
            </div>

            <h3>Paan Patta</h3>

            <p>
                Puja aur anushthan ke liye selected fresh leaves.
            </p>

        </div>


        <div class="home-flower-card">

            <div class="home-flower-icon">
                <img
                    src="assets/images/categories/custom.jpg"
                    alt="Daily Puja Items and Puja Kits"
                    loading="lazy"
                >  
            </div>

            <h3>Custom Flower Order</h3>

            <p>
                Special puja aur functions ke liye advance booking.
            </p>

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

    // Has the loader already been shown in this tab?
    if (sessionStorage.getItem("loaderShown")) {

        loader.remove();
        document.body.classList.remove("loading");
        return;

    }

    // Mark loader as shown for this tab
    sessionStorage.setItem("loaderShown", "true");

    setTimeout(function () {

        loader.classList.add("hide");

        setTimeout(function () {

            loader.remove();
            document.body.classList.remove("loading");

        }, 800);

    }, 5500);

});

</script>




<?php

require __DIR__ . '/includes/footer.php';

?>