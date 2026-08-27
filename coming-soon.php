<?php

require_once __DIR__ . '/config/app.php';

$pageTitle = 'Coming Soon — Nitya Ritual E-Store';

require __DIR__ . '/includes/header.php';

?>

<main class="coming-soon-page">

    <!-- Decorative top garland -->
    <div class="coming-soon-garland">

        <svg
            viewBox="0 0 1200 34"
            preserveAspectRatio="none"
            aria-hidden="true"
        >

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
                        cy="<?= ($x / 40) % 2 === 0 ? 14 : 22 ?>"
                        r="6"
                    />

                <?php endfor; ?>

            </g>

        </svg>

    </div>


    <section class="coming-soon-content">

        <span class="coming-soon-eyebrow">
            Nitya Ritual E-Store
        </span>


        <div class="coming-soon-divider">
            ✦
        </div>


        <h1>
            Coming Soon
        </h1>


        <p class="coming-soon-message">
            Yeh page abhi taiyaar ho raha hai.
            <br>
            Hum jald hi ise aapke liye lekar aa rahe hain.
        </p>


        <p class="coming-soon-subtext">
            शुभ कार्य शीघ्र आरम्भ होगा
        </p>


        <div class="coming-soon-ornament">

            <span></span>

            <span class="ornament-diya">
                🪔
            </span>

            <span></span>

        </div>


        <!-- <a
            href="index.php"
            class="coming-soon-home"
        >
            Back to Home
        </a> -->

    </section>

</main>


<?php

require __DIR__ . '/includes/footer.php';

?>