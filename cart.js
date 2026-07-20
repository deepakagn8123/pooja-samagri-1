// cart.js — shared cart logic (localStorage backed, with in-memory fallback)
const CART_KEY = "shubhsamagri_cart";
let _memCart = null; // fallback if localStorage is unavailable

function _loadCart(){
  try{
    const raw = localStorage.getItem(CART_KEY);
    return raw ? JSON.parse(raw) : [];
  }catch(e){
    return _memCart || [];
  }
}
function _saveCart(cart){
  try{
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
  }catch(e){
    _memCart = cart;
  }
}

function addToCart(id, qty=1){
  const cart = _loadCart();
  const existing = cart.find(i => i.id === id);
  if(existing){ existing.qty += qty; }
  else{ cart.push({ id, qty }); }
  _saveCart(cart);
  updateCartBadge();
  return cart;
}
function removeFromCart(id){
  let cart = _loadCart();
  cart = cart.filter(i => i.id !== id);
  _saveCart(cart);
  updateCartBadge();
  return cart;
}
function setQty(id, qty){
  const cart = _loadCart();
  const item = cart.find(i => i.id === id);
  if(item){
    item.qty = Math.max(1, qty);
    _saveCart(cart);
  }
  updateCartBadge();
  return cart;
}
function getCart(){ return _loadCart(); }
function getCartCount(){
  return _loadCart().reduce((sum, i) => sum + i.qty, 0);
}
function getCartTotal(){
  const cart = _loadCart();
  let total = 0;
  cart.forEach(i => {
    const p = typeof getProduct === "function" ? getProduct(i.id) : null;
    if(p) total += p.price * i.qty;
  });
  return total;
}
function updateCartBadge(){
  const badge = document.getElementById("cart-count");
  if(!badge) return;
  const count = getCartCount();
  badge.textContent = count;
  badge.style.display = count > 0 ? "flex" : "none";
}
document.addEventListener("DOMContentLoaded", updateCartBadge);
