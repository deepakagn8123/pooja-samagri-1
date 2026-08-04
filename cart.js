const CART_KEY = "nitya_ritual_estore_cart_v2";

let _memCart = [];


function _loadCart()
{
    try {

        const raw = localStorage.getItem(CART_KEY);

        if (!raw) {
            return [];
        }

        const parsed = JSON.parse(raw);

        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed.filter(item =>
            typeof item.slug === "string" &&
            item.slug.trim() !== "" &&
            Number(item.qty) > 0
        );

    } catch (error) {

        return _memCart;
    }
}


function _saveCart(cart)
{
    try {

        localStorage.setItem(
            CART_KEY,
            JSON.stringify(cart)
        );

    } catch (error) {

        _memCart = cart;
    }
}


function addToCart(slug, qty = 1)
{
    slug = String(slug).trim();
    qty = parseInt(qty, 10);

    if (!slug) {
        return _loadCart();
    }

    if (!Number.isInteger(qty) || qty < 1) {
        qty = 1;
    }


    const cart = _loadCart();

    const existing = cart.find(
        item => item.slug === slug
    );


    if (existing) {

        existing.qty += qty;

    } else {

        cart.push({
            slug: slug,
            qty: qty
        });
    }


    _saveCart(cart);

    updateCartBadge();

    return cart;
}


function removeFromCart(slug)
{
    let cart = _loadCart();

    cart = cart.filter(
        item => item.slug !== slug
    );

    _saveCart(cart);

    updateCartBadge();

    return cart;
}


function setQty(slug, qty)
{
    qty = parseInt(qty, 10);

    let cart = _loadCart();

    const item = cart.find(
        item => item.slug === slug
    );


    if (!item) {
        return cart;
    }


    if (!Number.isInteger(qty) || qty < 1) {

        return removeFromCart(slug);
    }


    item.qty = Math.min(qty, 100);

    _saveCart(cart);

    updateCartBadge();

    return cart;
}


function getCart()
{
    return _loadCart();
}


function getCartCount()
{
    return _loadCart().reduce(
        (total, item) => total + Number(item.qty),
        0
    );
}


function updateCartBadge()
{
    const badge = document.getElementById(
        "cart-count"
    );


    if (!badge) {
        return;
    }


    const count = getCartCount();

    badge.textContent = count;

    badge.style.display =
        count > 0
            ? "flex"
            : "none";
}


document.addEventListener(
    "DOMContentLoaded",
    updateCartBadge
);