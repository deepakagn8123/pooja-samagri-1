<footer>

    <div class="foot-grid">

        <div>

            <div
                class="logo-text"
                style="margin-bottom:12px;"
            >

                Shubh<span style="color:#FBD599;">
                    Samagri
                </span>

            </div>

            <p style="max-width:260px;">
                Puja samagri aur wedding items —
                local shehar mein same-day,
                poore Bharat mein shipping ke saath.
            </p>

        </div>


        <div>

            <h4>Categories</h4>

            <a href="categories.php">
    All Categories
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
            © <?= date('Y') ?> ShubhSamagri.
        </span>

        <span>
            Made for aapka business
        </span>

    </div>

</footer>

<script src="cart.js"></script>

<?php if (!empty($pageScripts)): ?>
    <?= $pageScripts ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.querySelector('.floating-puja-whatsapp');
    const button = document.getElementById('floatingWaButton');
    const close = document.getElementById('floatingWaClose');

    if (!wrapper || !button || !close) return;


    button.addEventListener('click', function () {

        const isOpen = wrapper.classList.toggle('open');

        button.setAttribute(
            'aria-expanded',
            isOpen ? 'true' : 'false'
        );

    });


    close.addEventListener('click', function () {

        wrapper.classList.remove('open');

        button.setAttribute(
            'aria-expanded',
            'false'
        );

    });


    document.addEventListener('click', function (event) {

        if (!wrapper.contains(event.target)) {

            wrapper.classList.remove('open');

            button.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    });

});
</script>

<!-- ==========================================================
     GLOBAL FLOATING PUJA LIST WHATSAPP
========================================================== -->

<div class="floating-puja-whatsapp">

    <div
        class="floating-wa-panel"
        id="floatingWaPanel"
    >

        <button
            type="button"
            class="floating-wa-close"
            id="floatingWaClose"
            aria-label="Close"
        >
            ×
        </button>


        <span class="floating-wa-eyebrow">
            Fastest way to order
        </span>


        <h3>
            Apni Puja List WhatsApp karein
        </h3>


        <p>
            Har product alag-alag search karne ki zaroorat nahi.
            Apni handwritten list ki photo ya text bhejiye —
            hum availability aur price confirm kar denge.
        </p>


        <div class="floating-wa-steps">

            <div>
                <b>1</b>
                <span>List ki photo bhejein</span>
            </div>

            <div>
                <b>2</b>
                <span>Price confirmation paayein</span>
            </div>

            <div>
                <b>3</b>
                <span>Delivery receive karein</span>
            </div>

        </div>


        <a
            href="contact.php"
            class="floating-wa-send"
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


    <button
        type="button"
        class="floating-wa-button"
        id="floatingWaButton"
        aria-label="Send Puja List on WhatsApp"
        aria-expanded="false"
    >

        <svg
            viewBox="0 0 24 24"
            fill="currentColor"
        >
            <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
        </svg>

        <span class="floating-wa-pulse"></span>

    </button>

</div>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>




<script>

document.addEventListener("DOMContentLoaded", function () {

    /* ==========================================================
       UNIVERSAL AOS
       Customer Website
    ========================================================== */


    const elements = document.querySelectorAll(
        "body *:not(script):not(style):not(noscript):not(svg):not(path):not(circle):not(rect):not(line):not(polyline):not(polygon)"
    );


    let animationIndex = 0;


    elements.forEach(function (element) {

        /* ------------------------------------------------------
           DON'T OVERWRITE MANUAL AOS
        ------------------------------------------------------ */

        if (element.hasAttribute("data-aos")) {
            return;
        }


        /* ------------------------------------------------------
           BASIC TAG EXCLUSIONS
        ------------------------------------------------------ */

        const tag = element.tagName.toLowerCase();

        const ignoredTags = [

            "html",
            "head",
            "body",
            "meta",
            "link",
            "title",
            "br",
            "option",
            "input",
            "textarea",
            "select"

        ];


        if (ignoredTags.includes(tag)) {
            return;
        }


        /* ======================================================
           PROTECTED UI
           These elements should NEVER receive automatic AOS.
        ====================================================== */

        const protectedSelector = [

    /* Header */

    ".store-header-top",
    ".store-header-inner",
    ".store-brand",
    ".store-search",
    ".store-actions",
    ".store-cart",
    ".cart-badge",

    /* Floating WhatsApp */

    ".home-puja-list",
    ".floating-puja-whatsapp",
    ".floating-wa-panel",
    ".floating-wa-button",
    ".floating-wa-pulse",
    ".floating-wa-close",
    ".floating-wa-send",

    /* Cart */

    ".add-to-cart",
    ".add-cart",
    ".cart-btn",
    ".buy-now",
    ".quantity-control",
    ".quantity-selector",
    ".product-quantity",

    /* Wishlist */

    ".wishlist",
    ".wishlist-btn",

    /* Navigation */

    ".pagination",
    ".page-link",

    /* Modals */

    ".modal",
    ".modal-backdrop",
    ".overlay"

].join(",");


if (
    element.matches(protectedSelector) ||
    element.closest(protectedSelector)
) {
    return;
}


        /* ------------------------------------------------------
           FIXED / STICKY ELEMENTS
           Don't automatically animate floating UI.
        ------------------------------------------------------ */

        const computedStyle =
            window.getComputedStyle(element);


        if (
            computedStyle.position === "fixed" ||
            computedStyle.position === "sticky"
        ) {
            return;
        }


        /* ------------------------------------------------------
           HIDDEN ELEMENTS
        ------------------------------------------------------ */

        if (
            computedStyle.display === "none" ||
            computedStyle.visibility === "hidden"
        ) {
            return;
        }


        /* ------------------------------------------------------
           IGNORE EMPTY ELEMENTS
        ------------------------------------------------------ */

        if (
            element.children.length === 0 &&
            element.textContent.trim() === "" &&
            !element.querySelector("img")
        ) {
            return;
        }


        /* ======================================================
           DIFFERENT ANIMATIONS
        ====================================================== */

        const animations = [

            "fade-up",
            "fade-right",
            "fade-left",
            "fade-down",
            "zoom-in",
            "zoom-in-up"

        ];


        element.setAttribute(
            "data-aos",
            animations[
                animationIndex % animations.length
            ]
        );


        /* ------------------------------------------------------
           DURATION
        ------------------------------------------------------ */

        element.setAttribute(
            "data-aos-duration",
            "700"
        );


        /* ------------------------------------------------------
           SMALL STAGGER
        ------------------------------------------------------ */

        element.setAttribute(
            "data-aos-delay",
            Math.min(
                (animationIndex % 6) * 60,
                300
            )
        );


        animationIndex++;

    });


    /* ==========================================================
       INITIALIZE AOS
    ========================================================== */

    AOS.init({

        duration: 700,

        easing: "ease-out-cubic",

        once: true,

        offset: 80,

        anchorPlacement: "top-bottom",

        mirror: false

    });

});




</script>



</body>
</html>