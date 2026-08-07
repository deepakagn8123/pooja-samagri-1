
<?php
$pageTitle = $pageTitle ?? 'Nitya Ritual E-Store';
?>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$showLoader = !isset($_SESSION['loader_shown']);

if ($showLoader) {
    $_SESSION['loader_shown'] = true;
}

$pageTitle = $pageTitle ?? 'Nitya Ritual E-Store';

?>

<!DOCTYPE html>
<html lang="hi">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

<link rel="stylesheet" href="style.css">

<script type="module" src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"></script>

</head>

<body>

<?php if ($showLoader): ?>
<!-- Page Loader -->
<div id="page-loader">

    <dotlottie-player
        src="assets/images/Diya.lottie"
        autoplay
        style="width:220px;height:220px;">
    </dotlottie-player>

</div>

<?php endif; ?>

<header class="store-header">

    <!-- =========================
         LAYER 1
    ========================== -->

    <div class="store-header-top">

        <div class="store-header-inner">


            <!-- BRAND -->

            <a href="index.php" class="store-brand">

<dotlottie-player
    class="brand-diya"
    src="assets/images/DiyaCropped.json"
    autoplay
    loop>
</dotlottie-player>

    <img
        src="assets/images/site-logo.png"
        alt="Shubh Samagri"
        class="brand-logo"
    >

    </a>


            <!-- SEARCH -->

            <form
                class="store-search"
                action="search.php"
                method="get"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle cx="11" cy="11" r="7"/>
                    <path d="M20 20l-4-4"/>
                </svg>

                <input
                    type="search"
                    name="q"
                    placeholder="Search for Puja Items, Wedding Items and more"
                >

            </form>


            <!-- ACTIONS -->

            <div class="store-actions">


                <!-- CART -->

                <a
                    href="cart.php"
                    class="store-cart"
                    aria-label="Cart"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>

                        <path
                            d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"
                        />
                    </svg>

                    <span
                        class="cart-badge"
                        id="cart-count"
                        style="display:none;"
                    >
                        0
                    </span>

                </a>


                <a
                    href="contact.php"
                    class="header-action-btn"
                >
                    Contact Us
                </a>


                <a
                    href="contact.php"
                    class="header-action-btn"
                >
                    Send Puja List
                </a>


                <a
                    href="services.php"
                    class="header-action-btn"
                >
                    Book Pandit Ji
                </a>


                <button
                    class="menu-btn"
                    id="menuBtn"
                    type="button"
                    aria-label="Menu"
                >
                    ☰
                </button>

            </div>

        </div>

    </div>



    <!-- =========================
         LAYER 2
    ========================== -->

    <nav
        class="store-category-nav"
        id="mainNav"
    >

        <ul>

            <li>
                <a href="puja-samagri.php">
                    Puja Items
                </a>
            </li>

            <li>
                <a href="wedding-items.php">
                    Wedding Items
                </a>
            </li>

            <li>
                <a href="puja-samagri.php">
                    Puja Flowers
                </a>
            </li>

            <li>
                <a href="categories.php">
                    All Category
                </a>
            </li>

        </ul>

    </nav>



    <!-- =========================
         LAYER 3 / ANNOUNCEMENT
    ========================== -->

    <div class="store-announcement">

        <a href="contact.php">

            <span>◉</span>

            Send your Puja List on WhatsApp

            <strong>
                — Contact Us
            </strong>

        </a>

    </div>

</header>