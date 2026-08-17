
<?php
$pageTitle = $pageTitle ?? 'Nitya Ritual E-Store';
?>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/app.php';

$pageTitle = $pageTitle ?? 'Nitya Ritual E-Store';

$headerCategories = [];

try {

    $stmt = $pdo->query("
        SELECT
            id,
            name,
            slug
        FROM categories
        WHERE is_active = 1
        ORDER BY sort_order ASC, name ASC
    ");

    $headerCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {

    $headerCategories = [];

}
?>


<!DOCTYPE html>
<html lang="hi">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

<link rel="stylesheet" href="style.css">

<script type="module" src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"></script>

<link
    rel="stylesheet"
    href="https://unpkg.com/aos@2.3.4/dist/aos.css"
>

<script>

(function () {

    const LOADER_KEY = "nitya_ritual_loader_shown";

    try {

        if (sessionStorage.getItem(LOADER_KEY) === "true") {

            document.documentElement.classList.add("loader-skip");

        } else {

            /*
             * Mark immediately.
             *
             * This means:
             * - refresh = no loader
             * - same tab navigation = no loader
             * - new tab = loader again
             */
            sessionStorage.setItem(LOADER_KEY, "true");

        }

    } catch (error) {

        console.warn("Loader sessionStorage unavailable:", error);

    }

})();

</script>

</head>

<body>
<!-- Page Loader -->
<div id="page-loader">

    <dotlottie-player
        id="loader-diya"
        src="assets/images/Diya.lottie"
        autoplay
        style="width:220px;height:220px;">
    </dotlottie-player>

</div>

<header class="store-header">

    <!-- =========================
         LAYER 1
    ========================== -->

    <div class="store-header-top">

        <div class="store-header-inner">


            <!-- BRAND -->

            <a href="index.php" class="store-brand">

<!-- <dotlottie-player
    class="brand-diya"
    src="assets/images/DiyaCropped.json"
    autoplay
    loop>
</dotlottie-player> -->

    <img
        src="assets/images/logo.svg"
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
                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                >

<dotlottie-player
    class="search-bar"
    src="assets/images/search.lottie"
    autoplay
    loop>
</dotlottie-player>

            </form>


            <!-- ACTIONS -->

            <div class="store-actions">


                <!-- CART -->


                <a
                    href="index.php"
                    class="header-action-btn"
                >
                    Home
                </a>


                <div class="category-dropdown">

    <button
        type="button"
        class="header-action-btn category-dropdown-btn"
    >
        Categories
        <span class="category-dropdown-arrow">⌄</span>
    </button>


    <div class="category-dropdown-menu">

        <?php if (!empty($headerCategories)): ?>

            <?php foreach ($headerCategories as $category): ?>

                <a
                    href="category.php?slug=<?= urlencode($category['slug']) ?>"
                    class="category-dropdown-item"
                >
                    <?= htmlspecialchars(
                        $category['name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </a>

            <?php endforeach; ?>

        <?php else: ?>

            <span class="category-dropdown-empty">
                No categories available
            </span>

        <?php endif; ?>

    </div>

</div>


                <a
                    href="services.php"
                    class="header-action-btn"
                >
                    Socials
                </a>

                <dotlottie-player
    class="brand-diya"
    src="assets/images/new.lottie"
    autoplay
    loop>
</dotlottie-player>

                

            </div>

        </div>

    </div>


</header>