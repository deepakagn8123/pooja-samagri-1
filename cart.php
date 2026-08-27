<?php

$pageTitle = 'Cart — Nitya Ritual E-Store';

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

<div
    id="order-modal"
    class="order-modal"
    aria-hidden="true"
>
    <div
        class="order-modal-backdrop"
        data-close-order-modal
    ></div>

    <div
        class="order-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="order-modal-title"
    >

        <button
            type="button"
            class="order-modal-close"
            id="close-order-modal"
            aria-label="Close"
        >
            &times;
        </button>

        <div class="order-modal-header">

            <span class="eyebrow">
                Order Details
            </span>

            <h2 id="order-modal-title">
                Complete Your Order
            </h2>

            <p>
                Delivery ke liye kuch basic details enter karein.
            </p>

        </div>


        <form id="order-details-form">

            <div class="order-field">

                <label for="order-name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="order-name"
                    name="name"
                    maxlength="100"
                    required
                    autocomplete="name"
                    placeholder="Enter your full name"
                >

            </div>


            <div class="order-field">

                <label for="order-phone">
                    Mobile Number
                </label>

                <input
                    type="tel"
                    id="order-phone"
                    name="phone"
                    maxlength="10"
                    required
                    autocomplete="tel"
                    inputmode="numeric"
                    placeholder="10-digit mobile number"
                >

            </div>


            <div class="order-field">

                <label for="order-address">
                    Delivery Address
                </label>

                <textarea
                    id="order-address"
                    name="address"
                    rows="3"
                    maxlength="500"
                    required
                    autocomplete="street-address"
                    placeholder="Enter your complete delivery address"
                ></textarea>

            </div>


            <label class="order-check-row">

                <input
                    type="checkbox"
                    id="call-before-delivery"
                    name="call_before_delivery"
                >

                <span>
                    Call me 15 minutes before delivery
                </span>

            </label>


            <label class="order-check-row">

                <input
                    type="checkbox"
                    id="add-order-note"
                    name="add_note"
                >

                <span>
                    Add a note
                </span>

            </label>


            <div
                class="order-field"
                id="order-note-wrap"
                style="display:none;"
            >

                <label for="order-note">
                    Your Note
                </label>

                <textarea
                    id="order-note"
                    name="note"
                    rows="3"
                    maxlength="500"
                    placeholder="Any special instructions?"
                ></textarea>

            </div>


            <div class="order-modal-total">

                <span>
                    Order Total
                </span>

                <strong id="order-modal-total">
                    ₹0
                </strong>

            </div>


            <button
                type="submit"
                class="wa-btn order-submit-btn"
            >

                Continue to WhatsApp

            </button>

        </form>

    </div>
</div>


<?php

ob_start();

?>

<script>

let currentCartProducts = [];
const ADMIN_WHATSAPP = "919352732506";
let latestCartData = null;


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
        latestCartData = data;  

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
                        href="categories.php"
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
        <path d="M20.52 3.48A11.84 11.84 0 0 0 12.08 0C5.56 0 .25 5.31.25 11.83c0 2.08.54 4.11 1.57 5.9L.16 23.75l6.16-1.62a11.82 11.82 0 0 0 5.76 1.47h.01c6.52 0 11.83-5.31 11.83-11.83 0-3.16-1.23-6.13-3.4-8.29ZM12.09 21.6h-.01a9.79 9.79 0 0 1-4.99-1.36l-.36-.21-3.66.96.98-3.57-.23-.37a9.78 9.78 0 1 1 8.27 4.55Zm5.37-7.34c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.19.29-.75.95-.92 1.14-.17.19-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.07-.15-.66-1.59-.9-2.18-.24-.57-.48-.49-.66-.5h-.56c-.19 0-.51.07-.78.36-.27.29-1.02 1-1.02 2.43s1.04 2.82 1.19 3.02c.15.19 2.05 3.13 4.96 4.39.69.3 1.23.48 1.65.61.69.22 1.32.19 1.82.12.55-.08 1.72-.7 1.97-1.38.24-.68.24-1.26.17-1.38-.07-.12-.27-.19-.56-.34Z"/>
    </svg>

    WhatsApp par checkout karein

</a>
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

function checkoutOnWhatsApp()
{
    if (
        !latestCartData ||
        !latestCartData.items ||
        !latestCartData.items.length
    ) {
        alert("Aapki cart khaali hai.");
        return;
    }

    document.getElementById(
        "order-modal-total"
    ).textContent = fmtPrice(
        latestCartData.subtotal
    );

    document.getElementById(
        "order-modal"
    ).classList.add("is-open");

    document.getElementById(
        "order-modal"
    ).setAttribute(
        "aria-hidden",
        "false"
    );

    document.getElementById(
        "order-name"
    ).focus();
}

const orderModal =
    document.getElementById("order-modal");

const closeOrderModal =
    document.getElementById("close-order-modal");

const orderForm =
    document.getElementById("order-details-form");

const addNoteCheckbox =
    document.getElementById("add-order-note");

const orderNoteWrap =
    document.getElementById("order-note-wrap");


function closeOrderDetailsModal()
{
    orderModal.classList.remove("is-open");

    orderModal.setAttribute(
        "aria-hidden",
        "true"
    );
}


closeOrderModal.addEventListener(
    "click",
    closeOrderDetailsModal
);


document.querySelector(
    "[data-close-order-modal]"
).addEventListener(
    "click",
    closeOrderDetailsModal
);


addNoteCheckbox.addEventListener(
    "change",
    function()
    {
        orderNoteWrap.style.display =
            this.checked
                ? "block"
                : "none";

        if (!this.checked) {
            document.getElementById(
                "order-note"
            ).value = "";
        }
    }
);

orderForm.addEventListener(
    "submit",
    async function(event)
    {
        event.preventDefault();


        if (
            !latestCartData ||
            !latestCartData.items ||
            !latestCartData.items.length
        ) {
            alert("Aapki cart khaali hai.");
            return;
        }


        const submitButton =
            orderForm.querySelector(
                'button[type="submit"]'
            );


        const formData =
            new FormData(orderForm);


        const name =
            String(
                formData.get("name") || ""
            ).trim();


        const phone =
            String(
                formData.get("phone") || ""
            ).trim();


        const address =
            String(
                formData.get("address") || ""
            ).trim();


        const callBeforeDelivery =
            formData.get(
                "call_before_delivery"
            ) === "on";


        const note =
            String(
                formData.get("note") || ""
            ).trim();


        /*
         * Client-side validation.
         */
        if (!name) {

            alert(
                "Please enter your name."
            );

            document
                .getElementById("order-name")
                .focus();

            return;
        }


        if (!/^[0-9]{10}$/.test(phone)) {

            alert(
                "Please enter a valid 10-digit mobile number."
            );

            document
                .getElementById("order-phone")
                .focus();

            return;
        }


        if (!address) {

            alert(
                "Please enter your delivery address."
            );

            document
                .getElementById("order-address")
                .focus();

            return;
        }


        /*
         * Prevent double-clicking the order button.
         */
        submitButton.disabled = true;

        submitButton.textContent =
            "Creating your order...";


        try {

            const response =
                await fetch(
                    "config/create-order.php",
                    {
                        method: "POST",

                        headers: {
                            "Content-Type":
                                "application/json"
                        },

                        body: JSON.stringify({

                            cart: getCart(),

                            name: name,

                            phone: phone,

                            address: address,

                            call_before_delivery:
                                callBeforeDelivery,

                            note: note

                        })
                    }
                );


            const data =
                await response.json();


            if (
                !response.ok ||
                !data.success
            ) {

                throw new Error(
                    data.message ||
                    "Unable to create order."
                );
            }


            /*
             * Build WhatsApp message from
             * SERVER-VERIFIED order data.
             */
            const lines = [

                "🙏 NITYA RITUAL E-STORE",

                "━━━━━━━━━━━━━━━━━━",

                "",

                `🧾 ORDER ID: ${data.order_number}`,

                "",

                "👤 CUSTOMER DETAILS",

                `Name: ${data.customer.name}`,

                `Mobile: ${data.customer.phone}`,

                `Address: ${data.customer.address}`,

                "",

                "📦 ORDER DETAILS",

                "━━━━━━━━━━━━━━━━━━",

                ""

            ];


            data.items.forEach(
                (product, index) =>
                {

                    lines.push(
                        `${index + 1}. ${product.name}`
                    );

                    lines.push(
                        `   Qty: ${product.qty}`
                    );

                    lines.push(
                        `   Price: ${fmtPrice(product.price)} ${product.unit || ""}`
                    );

                    lines.push(
                        `   Subtotal: ${fmtPrice(product.line_total)}`
                    );

                    lines.push("");

                }
            );


            lines.push(
                "━━━━━━━━━━━━━━━━━━"
            );


            lines.push(
                `💰 TOTAL: ${fmtPrice(data.subtotal)}`
            );


            lines.push("");


            lines.push(
                `📞 Call before delivery: ${
                    data.customer.call_before_delivery
                        ? "Yes"
                        : "No"
                }`
            );


            if (
                data.customer.note
            ) {

                lines.push("");

                lines.push(
                    "📝 CUSTOMER NOTE"
                );

                lines.push(
                    data.customer.note
                );
            }


            lines.push("");

            lines.push(
                "Please confirm the order and share payment details."
            );


            const message =
                lines.join("\n");


            const whatsappUrl =
                `https://wa.me/${ADMIN_WHATSAPP}?text=` +
                encodeURIComponent(
                    message
                );


            /*
             * Keep the message available as a
             * fallback if WhatsApp doesn't open.
             */
            window.generatedOrderMessage =
                message;

            window.generatedWhatsAppUrl =
                whatsappUrl;

            window.generatedOrderNumber =
                data.order_number;


            /*
             * Show the final WhatsApp screen.
             */
            showOrderReadyState(
                whatsappUrl
            );


        } catch (error) {

            console.error(error);

            alert(
                error.message ||
                "Order create nahi ho paaya. Please try again."
            );


            submitButton.disabled = false;

            submitButton.textContent =
                "Continue to WhatsApp";
        }

    }
);

function showOrderReadyState(whatsappUrl)
{
    const dialog =
        document.querySelector(
            ".order-modal-dialog"
        );

    dialog.innerHTML = `
        <button
            type="button"
            class="order-modal-close"
            id="close-order-modal"
            aria-label="Close"
        >
            &times;
        </button>

        <div class="order-modal-header">

            <span class="eyebrow">
                Order Ready
            </span>

            <h2>
                Your order is ready
            </h2>
            <p
    style="
        margin-top:8px;
        font-size:13px;
        color:var(--ink-soft);
    "
>
    Order ID:
    <strong>
        ${window.generatedOrderNumber}
    </strong>
</p>

            <p>
                Continue to WhatsApp to send your
                order details to us.
            </p>

        </div>


        <div class="order-modal-total">

            <span>
                Order Total
            </span>

            <strong>
                ${fmtPrice(latestCartData.subtotal)}
            </strong>

        </div>


        <a
    href="#"
    id="open-whatsapp-order"
    target="_blank"
    rel="noopener noreferrer"
    class="wa-btn order-submit-btn"
>
    Continue to WhatsApp
</a>


        <p
            style="
                text-align:center;
                margin:14px 0 8px;
                color:var(--ink-soft);
                font-size:13px;
            "
        >
            WhatsApp nahi khul raha?
        </p>


        <button
            type="button"
            id="copy-order-details"
            class="order-copy-btn"
        >
            Copy Order Details
        </button>

    `;

    document
    .getElementById("open-whatsapp-order")
    .href = whatsappUrl;


    document
        .getElementById("close-order-modal")
        .addEventListener(
            "click",
            closeOrderDetailsModal
        );


    document
        .getElementById("copy-order-details")
        .addEventListener(
            "click",
            async function()
            {
                try {

                    await navigator.clipboard.writeText(
                        window.generatedOrderMessage
                    );

                    this.textContent =
                        "✓ Order Details Copied";

                } catch (error) {

                    alert(
                        "Order details copy nahi ho paaye. Please WhatsApp manually open karein."
                    );
                }
            }
        );
}

renderCart();

</script>

<?php

$pageScripts = ob_get_clean();

require __DIR__ . '/includes/footer.php';

?>