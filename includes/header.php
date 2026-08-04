<?php

$pageTitle = $pageTitle ?? 'Nitya Ritual E-Store';

?>

<!DOCTYPE html>
<html lang="hi">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<header>

    <a
        href="index.php"
        class="logo"
        style="text-decoration:none;"
    >

        <svg class="logo-mark" viewBox="0 0 40 40" fill="none">

            <ellipse
                cx="20"
                cy="27"
                rx="15"
                ry="7"
                fill="#B8860B"
            />

            <path
                d="M20 26c-3 0-6-2-6-5 0-4 3-8 6-14 3 6 6 10 6 14 0 3-3 5-6 5z"
                fill="#E8890C"
            />

            <path
                d="M20 21c-1.4 0-2.6-1-2.6-2.3 0-1.7 1.3-3.5 2.6-6 1.3 2.5 2.6 4.3 2.6 6 0 1.3-1.2 2.3-2.6 2.3z"
                fill="#FBD599"
            />

        </svg>

        <div class="logo-text">
            Shubh<span>Samagri</span>
        </div>

    </a>


    <nav id="mainNav">

        <ul>

            <li>
                <a href="index.php">
                    Home
                </a>
            </li>

            <li>
                <a href="puja-samagri.php">
                    Puja Samagri
                </a>
            </li>

            <li>
                <a href="wedding-items.php">
                    Wedding Items
                </a>
            </li>

            <li>
                <a href="services.php">
                    Services
                </a>
            </li>

            <li>
                <a href="contact.php">
                    Contact
                </a>
            </li>

        </ul>

    </nav>


    <div class="nav-actions">

        <a
            href="cart.php"
            class="cart-link"
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
            class="nav-cta"
        >

            <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="currentColor"
            >

                <path
                    d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"
                />

            </svg>

            WhatsApp Order

        </a>

    </div>


    <button
        class="menu-btn"
        id="menuBtn"
        type="button"
        aria-label="Open menu"
        aria-expanded="false"
    >
        ☰
    </button>

</header>