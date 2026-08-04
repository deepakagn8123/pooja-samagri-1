<?php

$pageTitle = 'Cart — ShubhSamagri';

require __DIR__ . '/includes/header.php';

?>


<div class="page-banner">

    <span class="eyebrow">
        Your Cart
    </span>

    <h1>
        Aapki Shopping Cart
    </h1>

    <p>
        Yahan apne items review karein aur
        WhatsApp par order confirm karein.
    </p>

</div>


<div
    class="cart-wrap"
    id="cart-wrap"
>

    <div class="cart-empty">
        <p>Cart load ho rahi hai...</p>
    </div>

</div>


<?php

ob_start();

?>

<script>

let currentCartProducts = [];


function fmtPrice(price)
{
    return "₹" + Number(price).toLocaleString(
        "en-IN",
        {
            maximumFractionDigits: 2
        }
    );
}


function escapeHtml(value)
{
    const div = document.createElement("div");

    div.textContent = value ?? "";

    return div.innerHTML;
}


async function getValidatedCart()
{
    const response = await fetch(
        "config/cart-api.php",
        {
            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify({
                cart: getCart()
            })
        }
    );


    if (!response.ok) {
        throw new Error("Cart request failed.");
    }


    const data = await response.json();


    if (!data.success) {
        throw new Error(
            data.message || "Unable to load cart."
        );
    }


    return data;
}


async function renderCart()
{
    const wrap = document.getElementById("cart-wrap");


    try {

        const data = await getValidatedCart();

        currentCartProducts = data.items;


        /*
         * Remove products from localStorage that no longer
         * exist or have been disabled by the admin.
         */
        const validSlugs = new Set(
            data.items.map(item => item.slug)
        );


        const localCart = getCart();

        const cleanedCart = localCart.filter(
            item => validSlugs.has(item.slug)
        );


        if (cleanedCart.length !== localCart.length) {

            _saveCart(cleanedCart);

            updateCartBadge();
        }


        if (data.items.length === 0) {

            wrap.innerHTML = `
                <div class="cart-empty">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#8B1E3F"
                        stroke-width="1.5"
                    >
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>

                        <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/>
                    </svg>

                    <p style="margin-bottom:18px;">
                        Aapki cart abhi khaali hai.
                    </p>

                    <a
                        href="puja-samagri.php"
                        class="btn-primary"
                        style="display:inline-flex;"
                    >
                        Shopping shuru karein
                    </a>

                </div>
            `;

            return;
        }


        let rows = "";


        data.items.forEach(product => {

            const image = product.image
                ? `
                    <img
                        src="assets/images/products/${encodeURIComponent(product.image)}"
                        alt="${escapeHtml(product.name)}"
                        class="product-card-image"
                    >
                `
                : `
                    <div class="product-image-placeholder">
                        Image coming soon
                    </div>
                `;


            rows += `

                <div class="cart-row">

                    <div class="cart-row-icon">
                        ${image}
                    </div>


                    <div class="cart-row-name">

                        <a href="product.php?slug=${encodeURIComponent(product.slug)}">
                            ${escapeHtml(product.name)}
                        </a>

                        <div
                            style="
                                font-size:12px;
                                color:var(--ink-soft);
                                font-weight:400;
                                margin-top:2px;
                            "
                        >
                            ${fmtPrice(product.price)}
                            ${escapeHtml(product.unit)}
                            each
                        </div>

                    </div>


                    <div class="qty-stepper">

                        <button
                            onclick="updateQty(
                                '${escapeHtml(product.slug)}',
                                ${product.qty - 1}
                            )"
                        >
                            −
                        </button>

                        <span>
                            ${product.qty}
                        </span>

                        <button
                            onclick="updateQty(
                                '${escapeHtml(product.slug)}',
                                ${product.qty + 1}
                            )"
                        >
                            +
                        </button>

                    </div>


                    <div class="cart-row-price">
                        ${fmtPrice(product.line_total)}
                    </div>


                    <button
                        class="cart-row-remove"
                        onclick="removeItem('${escapeHtml(product.slug)}')"
                    >
                        Remove
                    </button>

                </div>
            `;
        });


        wrap.innerHTML = `

            <div class="cart-rows">
                ${rows}
            </div>


            <div class="cart-summary">

                <div class="cart-summary-row">

                    <span>
                        Subtotal
                    </span>

                    <span>
                        ${fmtPrice(data.subtotal)}
                    </span>

                </div>


                <div class="cart-summary-row">

                    <span>
                        Delivery
                    </span>

                    <span>
                        Confirm on WhatsApp
                    </span>

                </div>


                <div class="cart-summary-row total">

                    <span>
                        Total
                    </span>

                    <span>
                        ${fmtPrice(data.subtotal)}
                    </span>

                </div>


                <a
                    href="#"
                    class="wa-btn"
                    style="
                        width:100%;
                        justify-content:center;
                        margin-top:18px;
                    "
                    onclick="
                        checkoutOnWhatsApp();
                        return false;
                    "
                >

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/>
                    </svg>

                    WhatsApp par checkout karein

                </a>

            </div>
        `;


    } catch (error) {

        console.error(error);

        wrap.innerHTML = `

            <div class="cart-empty">

                <p>
                    Cart load nahi ho paayi.
                    Please page refresh karein.
                </p>

            </div>
        `;
    }
}


function updateQty(slug, qty)
{
    if (qty < 1) {

        removeItem(slug);

        return;
    }


    setQty(slug, qty);

    renderCart();
}


function removeItem(slug)
{
    removeFromCart(slug);

    renderCart();
}


async function checkoutOnWhatsApp()
{
    try {

        /*
         * Fetch again before checkout so we don't use
         * stale browser prices.
         */
        const data = await getValidatedCart();


        if (!data.items.length) {
            return;
        }


        const lines = [
            "Namaste! Mujhe yeh order karna hai:"
        ];


        data.items.forEach(product => {

            lines.push(
                `- ${product.name} x${product.qty} — ${fmtPrice(product.line_total)}`
            );

        });


        lines.push(
            `Total: ${fmtPrice(data.subtotal)}`
        );


        window.open(
            `https://wa.me/910000000000?text=${encodeURIComponent(lines.join("\n"))}`,
            "_blank"
        );


    } catch (error) {

        console.error(error);

        alert(
            "Latest product details load nahi ho paayi. Please dobara try karein."
        );
    }
}


renderCart();

</script>

<?php

$pageScripts = ob_get_clean();

require __DIR__ . '/includes/footer.php';

?>