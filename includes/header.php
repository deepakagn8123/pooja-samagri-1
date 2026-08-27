
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

<script>
(function () {

    try {

        if (
            sessionStorage.getItem("nitya_ritual_loader_shown") === "true"
        ) {

            document.write(
                '<style>#page-loader{display:none!important}</style>'
            );

        } else {

            sessionStorage.setItem(
                "nitya_ritual_loader_shown",
                "true"
            );

        }

    } catch (e) {}

})();
</script>

<link rel="stylesheet" href="style.css">

<script type="module" src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"></script>

<link
    rel="stylesheet"
    href="https://unpkg.com/aos@2.3.4/dist/aos.css"
>


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

    <!-- <img
        src="assets/images/logo.svg"
        alt="Shubh Samagri"
        class="brand-logo"
    > -->

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


               <a href="about.php" class="header-action-btn">
                        About Us
                    </a>

                    <a href="services.php" class="header-action-btn">
                        Socials
                    </a>
                    <!-- WhatsApp ke icon on top ke liye -->
                    <a href="https://wa.me/919999999999" target="_blank" rel="noopener noreferrer"
                        class="header-whatsapp-btn" title="Chat on WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="28" height="28"
                            fill="#25D366">
                            <path
                                d="M16 0C7.163 0 0 7.163 0 16c0 2.822.736 5.47 2.027 7.77L0 32l8.43-2.007A15.934 15.934 0 0 0 16 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.267 13.267 0 0 1-6.746-1.84l-.484-.287-5.006 1.193 1.216-4.867-.316-.5A13.254 13.254 0 0 1 2.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.27-9.874c-.398-.2-2.355-1.162-2.72-1.294-.366-.133-.633-.2-.9.2-.266.397-1.031 1.294-1.264 1.56-.232.265-.465.299-.863.1-.398-.2-1.68-.619-3.2-1.977-1.183-1.056-1.981-2.361-2.213-2.759-.232-.398-.025-.613.174-.812.178-.178.398-.465.598-.698.2-.232.265-.398.398-.664.133-.266.066-.499-.033-.698-.1-.2-.9-2.162-1.232-2.96-.324-.778-.654-.673-.9-.685l-.766-.013c-.266 0-.698.1-1.065.499-.366.398-1.397 1.365-1.397 3.328 0 1.963 1.43 3.86 1.629 4.127.2.265 2.814 4.296 6.82 6.026.953.412 1.697.658 2.277.842.957.305 1.828.262 2.516.159.767-.114 2.355-.963 2.688-1.893.332-.93.332-1.727.232-1.893-.099-.166-.365-.265-.763-.465z" />
                        </svg>
                    </a>
                <!-- <dotlottie-player
    class="brand-diya"
    src="assets/images/new.lottie"
    autoplay
    loop>
</dotlottie-player> -->

                

            </div>

        </div>

    </div>


</header>