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


/* =========================================================
   HOMEPAGE CATEGORIES
   Only active top-level categories marked for homepage
========================================================= */

$homeCategories = [];

try {

    $stmt = $pdo->query("
        SELECT
            id,
            name,
            slug,
            image,
            description,
            sort_order
        FROM categories
        WHERE is_active = 1
          AND show_on_homepage = 1
          AND parent_id IS NULL
        ORDER BY
            sort_order ASC,
            name ASC
    ");

    $homeCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {

    $homeCategories = [];

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

<?php

$pageScripts = ob_get_clean();

require __DIR__ . '/includes/header.php';

?>


<section class="home-hero">

    <div class="hero-slider" id="heroSlider">

        <!-- SLIDE 1 -->
        <div class="hero-slide ">

            <img
                src="assets/images/categories/image (1).png"
                alt="Nitya Ritual E-Store - Puja Samagri"
                class="hero-slide-image"
            >

            <div class="hero-slide-overlay"></div>

            <div class="hero-slide-content">

                <span class="hero-eyebrow">
                    Nitya Ritual E-Store
                </span>

                <h1>
                    Everything You Need<br>
                    for Every Sacred Occasion
                </h1>

                <p>
                    Authentic puja essentials, carefully curated
                    for your rituals and celebrations.
                </p>

                <a href="categories.php" class="hero-btn">
                    Shop Puja Samagri
                    <span>→</span>
                </a>

            </div>

        </div>


        <!-- SLIDE 2 -->
        <div class="hero-slide">

            <img
                src="assets/images/categories/image (2).png"
                alt="Authentic Hindu Puja Essentials"
                class="hero-slide-image"
            >

            <div class="hero-slide-overlay"></div>

            <div class="hero-slide-content">

                <span class="hero-eyebrow">
                    Tradition You Can Trust
                </span>

                <h2>
                    Authentic Essentials<br>
                    for Your Sacred Rituals
                </h2>

                <p>
                    Quality products selected with care
                    for every puja and occasion.
                </p>

                <a href="categories.php" class="hero-btn">
                    Explore Collection
                    <span>→</span>
                </a>

            </div>

        </div>


        <!-- SLIDE 3 -->
        <div class="hero-slide">

            <img
                src="assets/images/categories/image (3).png"
                alt="Complete Puja Collection"
                class="hero-slide-image"
            >

            <div class="hero-slide-overlay"></div>

            <div class="hero-slide-content">

                <span class="hero-eyebrow">
                    Everything in One Place
                </span>

                <h2>
                    From Everyday Puja<br>
                    to Special Occasions
                </h2>

                <p>
                    Discover samagri, flowers, diyas,
                    devotional items and more.
                </p>

                <a href="categories.php" class="hero-btn">
                    Shop Now
                    <span>→</span>
                </a>

            </div>

        </div>


        <!-- SLIDE 4 -->
        <div class="hero-slide">

            <img
                src="assets/images/categories/image (4).png"
                alt="Puja and Festival Collection"
                class="hero-slide-image"
            >

            <div class="hero-slide-overlay"></div>

            <div class="hero-slide-content">

                <span class="hero-eyebrow">
                    Celebrate Every Sacred Moment
                </span>

                <h2>
                    Bring Home<br>
                    the Tradition
                </h2>

                <p>
                    Beautiful essentials for festivals,
                    celebrations and everyday devotion.
                </p>

                <a href="categories.php" class="hero-btn">
                    Explore Collection
                    <span>→</span>
                </a>

            </div>

        </div>


        <!-- CONTROLS -->

        <button
            type="button"
            class="hero-control hero-prev"
            id="heroPrev"
            aria-label="Previous banner"
        >
            ‹
        </button>

        <button
            type="button"
            class="hero-control hero-next"
            id="heroNext"
            aria-label="Next banner"
        >
            ›
        </button>


        <!-- DOTS -->

        <div class="hero-dots" id="heroDots">

            <button
                type="button"
                class="hero-dot active"
                data-slide="0"
                aria-label="Go to banner 1"
            ></button>

            <button
                type="button"
                class="hero-dot"
                data-slide="1"
                aria-label="Go to banner 2"
            ></button>

            <button
                type="button"
                class="hero-dot"
                data-slide="2"
                aria-label="Go to banner 3"
            ></button>

            <button
                type="button"
                class="hero-dot"
                data-slide="3"
                aria-label="Go to banner 4"
            ></button>

        </div>

    </div>

</section>

<!-- ========================================================
     SHOP BY CATEGORY
======================================================== -->

<section class="home-shop-section ">

    <div class="home-section-title">

        <h2>Shop by Category</h2>

        <a href="categories.php" class="home-see-all">
            See All →
        </a>

    </div>


    <?php if (!empty($homeCategories)): ?>

        <div class="home-category-grid">

            <?php foreach ($homeCategories as $category): ?>

                <a
                    href="category.php?slug=<?= rawurlencode($category['slug']) ?>"
                    class="home-category-card"
                >

                    <div class="home-category-image">

                        <?php if (!empty($category['image'])): ?>

                            <img
                                src="assets/images/categories/<?= rawurlencode($category['image']) ?>"
                                alt="<?= e($category['name']) ?>"
                                loading="lazy"
                            >

                        <?php else: ?>

                            <div class="category-image-placeholder">
                                🪔
                            </div>

                        <?php endif; ?>

                    </div>


                    <h3>
                        <?= e($category['name']) ?>
                    </h3>

                </a>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="home-products-empty">

            <p>
                Categories jaldi yahan dikhai denge.
            </p>

            <a
                href="categories.php"
                class="btn-primary"
            >
                Browse All Categories
            </a>

        </div>

    <?php endif; ?>

</section>



<!-- ========================================================
     PUJA ORGANIZER BANNER
======================================================== -->

<section class="home-organizer organizer-background">

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

            <a href="categories.php"
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

<section class="serviceable-area serviceable-background">

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

        <a href="categories.php" class="home-see-all">
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

    <!-- PRODUCT IMAGE -->
    <a
        href="product.php?slug=<?= rawurlencode($product['slug']) ?>"
        class="home-product-image"
    >

        <div class="home-product-image-frame">

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
                            fill="#F7E2B2"
                        />

                        <path
                            d="M40 65
                               C40 42 50 25 50 25
                               C50 25 60 42 60 65Z"
                            fill="#8A1621"
                        />
                    </svg>

                </div>

            <?php endif; ?>

        </div>

    </a>


    <!-- PRODUCT INFORMATION -->
    <div class="home-product-info">


        <!-- BADGE IS NOT OVER IMAGE -->
        <?php if (!empty($product['badge'])): ?>

            <span class="home-product-badge">
                <?= e($product['badge']) ?>
            </span>

        <?php endif; ?>


        <!-- CATEGORY -->
        <?php if (!empty($product['category_name'])): ?>

            <span class="home-product-category">
                <?= e($product['category_name']) ?>
            </span>

        <?php endif; ?>


        <!-- TITLE -->
        <a
            href="product.php?slug=<?= rawurlencode($product['slug']) ?>"
            class="home-product-name"
        >
            <?= e($product['name']) ?>
        </a>


        <!-- SHORT DETAILS -->
        <div class="home-product-details">

            <?php if (!empty($product['unit'])): ?>

                <span>
                    <?= e($product['unit']) ?>
                </span>

            <?php endif; ?>

            <span>
                Quality Assured
            </span>

        </div>


        <!-- PRICE + CART -->
        <div class="home-product-bottom">


            <div class="home-product-price">

                <?php if (!empty($product['old_price'])): ?>

                    <span class="home-product-old-price">
                        ₹<?= number_format((float)$product['old_price'], 0) ?>
                    </span>

                <?php endif; ?>

                <strong>
                    ₹<?= number_format((float)$product['price'], 0) ?>
                </strong>

            </div>


            <button
                type="button"
                class="home-product-add"
                onclick="homeAddToCart(<?= js_json($product['slug']) ?>)"
                aria-label="Add <?= e($product['name']) ?> to cart"
            >

                <span>Add to Cart</span>

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>

                    <path
                        d="M1 1h4l2.7 13.4
                           a2 2 0 0 0 2 1.6
                           h9.7a2 2 0 0 0 2-1.6L23 6H6"
                    />
                </svg>

            </button>


        </div>


    </div>

</article>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <div class="home-products-empty">

            <p>
                Products jaldi yahan dikhai denge.
            </p>

            <a href="categories.php" class="btn-primary">
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
                Jaipur Based
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

function handleHomeProductAdd(button, slug) {

    if (button.classList.contains("adding")) {
        return;
    }

    const label = button.querySelector("span");

    button.classList.add("adding");

    if (label) {
        label.textContent = "Adding...";
    }

    if (typeof addToCart !== "function") {
        console.error("cart.js is not loaded.");

        button.classList.remove("adding");

        if (label) {
            label.textContent = "Add to Cart";
        }

        return;
    }


    addToCart(slug, 1);


    setTimeout(function () {

        button.classList.remove("adding");

        button.classList.add("added");

        if (label) {
            label.textContent = "Added ✓";
        }


        setTimeout(function () {

            button.classList.remove("added");

            if (label) {
                label.textContent = "Add to Cart";
            }

        }, 1200);

    }, 350);

}

// Normal Lottie js, loads at every refresh

// document.body.classList.add("loading");
window.addEventListener("load", function () {

    const loader = document.getElementById("page-loader");

    if (!loader) {
        return;
    }

    /*
     * If the inline CSS hid the loader,
     * remove it and do nothing else.
     */
    if (getComputedStyle(loader).display === "none") {
        loader.remove();
        return;
    }

    /*
     * First load in this tab.
     */
    setTimeout(function () {

        loader.classList.add("hide");

        setTimeout(function () {

            loader.remove();

        }, 800);

    }, 5500);

});
</script>




<script>
document.addEventListener("DOMContentLoaded", function () {

    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".hero-dot");

    const prevButton = document.getElementById("heroPrev");
    const nextButton = document.getElementById("heroNext");

    if (!slides.length) {
        return;
    }

    let currentSlide = 0;

    let autoplayTimer;


    function showSlide(index) {

        if (index < 0) {
            index = slides.length - 1;
        }

        if (index >= slides.length) {
            index = 0;
        }

        slides.forEach(function (slide, i) {
            slide.classList.toggle("active", i === index);
        });


        dots.forEach(function (dot, i) {
            dot.classList.toggle("active", i === index);
        });


        currentSlide = index;
    }


    function nextSlide() {

        showSlide(currentSlide + 1);

    }


    function previousSlide() {

        showSlide(currentSlide - 1);

    }


    function startAutoplay() {

        clearInterval(autoplayTimer);

        autoplayTimer = setInterval(
            nextSlide,
            6000
        );

    }


    nextButton.addEventListener(
        "click",
        function () {

            nextSlide();
            startAutoplay();

        }
    );


    prevButton.addEventListener(
        "click",
        function () {

            previousSlide();
            startAutoplay();

        }
    );


    dots.forEach(function (dot) {

        dot.addEventListener(
            "click",
            function () {

                showSlide(
                    Number(dot.dataset.slide)
                );

                startAutoplay();

            }
        );

    });


    showSlide(0);

    startAutoplay();

});
</script>



<?php

require __DIR__ . '/includes/footer.php';

?>