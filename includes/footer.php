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

            <a href="about.php">
                About US
            </a>

        </div>


        <div>

            <h4>Contact</h4>

            <a href="#">
                WhatsApp: +91 93527 32506
            </a>

            <a href="#">
                mail.nitya@gmail.com
            </a>

            <a href="#">
                Local city + Pan-India
            </a>

        </div>

    </div>


    <div class="foot-bottom">

        <span>
            © <?= date('Y') ?> Nitya Ritual E-Store.
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


<!-- =========================
     FLOATING CART
========================== -->

<a
    href="cart.php"
    class="floating-cart"
    aria-label="Shopping Cart"
>

    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
    >
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>

        <path
            d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"
        />
    </svg>

    <span
        class="floating-cart-count"
        id="cart-count"
        style="display:none;"
    >
        0
    </span>

</a>

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
            href="https://wa.me/919352732506"
            class="floating-wa-send"
        >

            <svg
    width="18"
    height="18"
    viewBox="0 0 24 24"
    fill="currentColor"
>
    <path d="M20.52 3.48A11.84 11.84 0 0 0 12.08 0C5.56 0 .25 5.31.25 11.83c0 2.08.54 4.11 1.57 5.9L.16 23.75l6.16-1.62a11.82 11.82 0 0 0 5.76 1.47h.01c6.52 0 11.83-5.31 11.83-11.83 0-3.16-1.23-6.13-3.4-8.29ZM12.09 21.6h-.01a9.79 9.79 0 0 1-4.99-1.36l-.36-.21-3.66.96.98-3.57-.23-.37a9.78 9.78 0 1 1 8.27 4.55Zm5.37-7.34c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.19.29-.75.95-.92 1.14-.17.19-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.07-.15-.66-1.59-.9-2.18-.24-.57-.48-.49-.66-.5h-.56c-.19 0-.51.07-.78.36-.27.29-1.02 1-1.02 2.43s1.04 2.82 1.19 3.02c.15.19 2.05 3.13 4.96 4.39.69.3 1.23.48 1.65.61.69.22 1.32.19 1.82.12.55-.08 1.72-.7 1.97-1.38.24-.68.24-1.26.17-1.38-.07-.12-.27-.19-.56-.34Z"/>
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
        <path d="M20.52 3.48A11.84 11.84 0 0 0 12.08 0C5.56 0 .25 5.31.25 11.83c0 2.08.54 4.11 1.57 5.9L.16 23.75l6.16-1.62a11.82 11.82 0 0 0 5.76 1.47h.01c6.52 0 11.83-5.31 11.83-11.83 0-3.16-1.23-6.13-3.4-8.29ZM12.09 21.6h-.01a9.79 9.79 0 0 1-4.99-1.36l-.36-.21-3.66.96.98-3.57-.23-.37a9.78 9.78 0 1 1 8.27 4.55Zm5.37-7.34c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.19.29-.75.95-.92 1.14-.17.19-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.07-.15-.66-1.59-.9-2.18-.24-.57-.48-.49-.66-.5h-.56c-.19 0-.51.07-.78.36-.27.29-1.02 1-1.02 2.43s1.04 2.82 1.19 3.02c.15.19 2.05 3.13 4.96 4.39.69.3 1.23.48 1.65.61.69.22 1.32.19 1.82.12.55-.08 1.72-.7 1.97-1.38.24-.68.24-1.26.17-1.38-.07-.12-.27-.19-.56-.34Z"/>
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


<script>
document.addEventListener('DOMContentLoaded', function () {

    const menuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileMenuOverlay');
    const closeButton = document.getElementById('mobileMenuClose');

    const categoryToggle =
        document.getElementById('mobileCategoryToggle');

    const categoryList =
        document.getElementById('mobileCategoryList');


    if (!menuToggle || !mobileMenu || !overlay) {
        return;
    }


    /* =========================================
       OPEN MENU
    ========================================== */

    function openMenu() {

        mobileMenu.classList.add('active');

        overlay.classList.add('active');

        menuToggle.classList.add('active');

        document.body.classList.add('mobile-menu-open');

        menuToggle.setAttribute(
            'aria-expanded',
            'true'
        );

        mobileMenu.setAttribute(
            'aria-hidden',
            'false'
        );

        menuToggle.setAttribute(
            'aria-label',
            'Close menu'
        );
    }


    /* =========================================
       CLOSE MENU
    ========================================== */

    function closeMenu() {

        mobileMenu.classList.remove('active');

        overlay.classList.remove('active');

        menuToggle.classList.remove('active');

        document.body.classList.remove('mobile-menu-open');

        menuToggle.setAttribute(
            'aria-expanded',
            'false'
        );

        mobileMenu.setAttribute(
            'aria-hidden',
            'true'
        );

        menuToggle.setAttribute(
            'aria-label',
            'Open menu'
        );
    }


    /* =========================================
       HAMBURGER
    ========================================== */

    menuToggle.addEventListener('click', function () {

        if (mobileMenu.classList.contains('active')) {

            closeMenu();

        } else {

            openMenu();

        }

    });


    /* =========================================
       CLOSE BUTTON
    ========================================== */

    if (closeButton) {

        closeButton.addEventListener(
            'click',
            closeMenu
        );

    }


    /* =========================================
       OVERLAY
    ========================================== */

    overlay.addEventListener(
        'click',
        closeMenu
    );


    /* =========================================
       ESCAPE
    ========================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                mobileMenu.classList.contains('active')
            ) {

                closeMenu();

            }

        }
    );


    /* =========================================
       CATEGORIES ACCORDION
    ========================================== */

    if (categoryToggle && categoryList) {

        categoryToggle.addEventListener(
            'click',
            function () {

                const isOpen =
                    categoryList.classList.toggle('active');

                categoryToggle.classList.toggle(
                    'active',
                    isOpen
                );

                categoryToggle.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
                );

            }
        );

    }


    /* =========================================
       CLOSE AFTER NAVIGATION
    ========================================== */

    mobileMenu
        .querySelectorAll(
            'a:not(.mobile-category-item)'
        )
        .forEach(function (link) {

            link.addEventListener(
                'click',
                closeMenu
            );

        });


    /* =========================================
       RESET WHEN GOING DESKTOP
    ========================================== */

    window.addEventListener(
        'resize',
        function () {

            if (window.innerWidth > 768) {

                closeMenu();

            }

        }
    );

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const nameElement =
        document.getElementById("navbarBrandName");

    const subtitleElement =
        document.getElementById("navbarBrandSubtitle");

    if (!nameElement || !subtitleElement) {
        return;
    }


    const nameText = "Nitya";
    const subtitleText = "E-RITUAL STORE";


    let nameIndex = 0;
    let subtitleIndex = 0;

    let deleting = false;


    function typeName() {

        if (!deleting) {

            nameElement.textContent =
                nameText.substring(0, nameIndex);

            if (nameIndex < nameText.length) {

                nameIndex++;

                setTimeout(typeName, 170);

            } else {

                /* Small pause before subtitle */
                setTimeout(typeSubtitle, 450);
            }

        } else {

            nameElement.textContent =
                nameText.substring(0, nameIndex);

            if (nameIndex > 0) {

                nameIndex--;

                setTimeout(typeName, 90);

            } else {

                /* Restart */
                deleting = false;

                setTimeout(typeName, 800);
            }
        }
    }


    function typeSubtitle() {

        subtitleElement.textContent =
            subtitleText.substring(0, subtitleIndex);

        if (subtitleIndex < subtitleText.length) {

            subtitleIndex++;

            setTimeout(typeSubtitle, 90);

        } else {

            /*
             * Keep the complete logo visible
             * for a while before erasing.
             */
            setTimeout(startDeleting, 3000);
        }
    }


    function startDeleting() {

        deleting = true;

        deleteSubtitle();
    }


    function deleteSubtitle() {

        subtitleElement.textContent =
            subtitleText.substring(0, subtitleIndex);

        if (subtitleIndex > 0) {

            subtitleIndex--;

            setTimeout(deleteSubtitle, 55);

        } else {

            setTimeout(typeName, 300);
        }
    }


    /* Start */
    typeName();

});
</script>
</body>
</html>