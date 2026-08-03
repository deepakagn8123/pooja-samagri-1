<?php

require_once __DIR__ . '/config/app.php';

$products = getProductsByCategory($pdo, 'puja-samagri');

?>

<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Puja Samagri &amp; Kits — ShubhSamagri</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo">
    <svg class="logo-mark" viewBox="0 0 40 40" fill="none">
      <ellipse cx="20" cy="27" rx="15" ry="7" fill="#B8860B"/>
      <path d="M20 26c-3 0-6-2-6-5 0-4 3-8 6-14 3 6 6 10 6 14 0 3-3 5-6 5z" fill="#E8890C"/>
      <path d="M20 21c-1.4 0-2.6-1-2.6-2.3 0-1.7 1.3-3.5 2.6-6 1.3 2.5 2.6 4.3 2.6 6 0 1.3-1.2 2.3-2.6 2.3z" fill="#FBD599"/>
    </svg>
    <div class="logo-text">Shubh<span>Samagri</span></div>
  </div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="puja-samagri.php" class="active">Puja Samagri</a></li>
      <li><a href="wedding-items.php">Wedding Items</a></li>
      <li><a href="services.php">Services</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
  <div class="nav-actions">
    <a href="cart.php" class="cart-link" aria-label="Cart">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
      <span class="cart-badge" id="cart-count" style="display:none;">0</span>
    </a>
    <a href="contact.php" class="nav-cta">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/></svg>
      WhatsApp Order
    </a>
  </div>
 <button
    class="menu-btn"
    id="menuBtn"
    aria-label="Open menu"
    aria-expanded="false"
    type="button"
  >
    ☰
  </button>
</header>

<div class="garland"><svg viewBox="0 0 1200 34" preserveAspectRatio="none"><path d="M0 4 Q60 30 120 4 T240 4 T360 4 T480 4 T600 4 T720 4 T840 4 T960 4 T1080 4 T1200 4" stroke="#5C7A4A" stroke-width="2" fill="none"/><g fill="#E8890C"><circle cx="20" cy="14" r="6"/><circle cx="60" cy="22" r="6"/><circle cx="100" cy="14" r="6"/><circle cx="140" cy="22" r="6"/><circle cx="180" cy="14" r="6"/><circle cx="220" cy="22" r="6"/><circle cx="260" cy="14" r="6"/><circle cx="300" cy="22" r="6"/><circle cx="340" cy="14" r="6"/><circle cx="380" cy="22" r="6"/><circle cx="420" cy="14" r="6"/><circle cx="460" cy="22" r="6"/><circle cx="500" cy="14" r="6"/><circle cx="540" cy="22" r="6"/><circle cx="580" cy="14" r="6"/><circle cx="620" cy="22" r="6"/><circle cx="660" cy="14" r="6"/><circle cx="700" cy="22" r="6"/><circle cx="740" cy="14" r="6"/><circle cx="780" cy="22" r="6"/><circle cx="820" cy="14" r="6"/><circle cx="860" cy="22" r="6"/><circle cx="900" cy="14" r="6"/><circle cx="940" cy="22" r="6"/><circle cx="980" cy="14" r="6"/><circle cx="1020" cy="22" r="6"/><circle cx="1060" cy="14" r="6"/><circle cx="1100" cy="22" r="6"/><circle cx="1140" cy="14" r="6"/><circle cx="1180" cy="22" r="6"/></g></svg></div>

<div class="page-banner">
  <span class="eyebrow">Puja Samagri</span>
  <h1>Roz ki puja se anushthan tak</h1>
  <p>Shuddh aur taaza samagri — ready-made kits se lekar individual items tak, sab kuch ek jagah.</p>
</div>

<section class="block">
  <div class="section-head">
    <span class="eyebrow">Best sellers</span>
    <h2>Ready-made puja kits</h2>
  </div>
  <div class="prod-grid">
    <div class="prod-card">
      <a href="product.php?id=satyanarayan-kit" class="prod-link">
        <div class="prod-visual"><span class="sale-badge">15% OFF</span><img src="assets/images/satyanarayan_puja_kit.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Satyanarayan Puja Kit</h4><div class="price"><s>₹599</s>₹509</div><span class="tag">Complete kit</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('satyanarayan-kit')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=ganesh-chaturthi-kit" class="prod-link">
        <div class="prod-visual"><span class="sale-badge">10% OFF</span><img src="assets/images/Ganesh_Chaturthi_Kit.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Ganesh Chaturthi Kit</h4><div class="price"><s>₹449</s>₹404</div><span class="tag">Festival special</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('ganesh-chaturthi-kit')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=durva-sindoor-set" class="prod-link">
        <div class="prod-visual"><img src="assets/images/durva_sindoor_set.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Durva &amp; Sindoor Set</h4><div class="price">₹129</div><span class="tag">Everyday essential</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('durva-sindoor-set')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=agarbatti-dhoop-combo" class="prod-link">
        <div class="prod-visual"><span class="sale-badge">20% OFF</span><img src="assets/images/Agarbatti_&_Dhoop_Combo.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Agarbatti &amp; Dhoop Combo</h4><div class="price"><s>₹249</s>₹199</div><span class="tag">Daily use</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('agarbatti-dhoop-combo')" aria-label="Add to cart">+</button>
    </div>
  </div>
</section>

<section class="block" style="padding-top:0;">
  <div class="section-head">
    <span class="eyebrow">Idols &amp; metal articles</span>
    <h2>Peetal aur tamba ke bartan</h2>
  </div>
  <div class="prod-grid">
    <div class="prod-card">
      <a href="product.php?id=brass-ganesh-idol" class="prod-link">
        <div class="prod-visual"><img src="assets/images/Brass_Ganesh_Idol.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Brass Ganesh Idol</h4><div class="price">₹899</div><span class="tag">6 inch</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('brass-ganesh-idol')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=brass-diya-pair" class="prod-link">
        <div class="prod-visual"><img src="assets/images/Brass-Diya_(Pair).png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Brass Diya (Pair)</h4><div class="price">₹249</div><span class="tag">Handcrafted</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('brass-diya-pair')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=panchpradeep" class="prod-link">
        <div class="prod-visual"><img src="assets/images/panchpradeep.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Panchpradeep</h4><div class="price">₹649</div><span class="tag">5-wick brass lamp</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('panchpradeep')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=copper-aachmani" class="prod-link">
        <div class="prod-visual"><img src="assets/images/coppper_aachmani_panchpatra.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Copper Aachmani-Panchpatra</h4><div class="price">₹399</div><span class="tag">Pure copper</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('copper-aachmani')" aria-label="Add to cart">+</button>
    </div>
  </div>
</section>

<section class="block" style="padding-top:0;">
  <div class="section-head">
    <span class="eyebrow">Fresh daily</span>
    <h2>Taaza puja phool</h2>
    <p>Genda mala, tulsi patta, paan — daily subscription (abhi sirf aapke shehar mein available).</p>
  </div>
  <div class="prod-grid">
    <div class="prod-card">
      <a href="product.php?id=genda-mala" class="prod-link">
        <div class="prod-visual"><img src="assets/images/genda_phool _mala.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Genda Phool Mala</h4><div class="price">₹49 /day</div><span class="tag">Subscription</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('genda-mala')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=tulsi-patta" class="prod-link">
        <div class="prod-visual"><img src="assets/images/tulsi_patta.png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Tulsi Patta</h4><div class="price">₹19 /pack</div><span class="tag">Fresh cut</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('tulsi-patta')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=paan-patta" class="prod-link">
        <div class="prod-visual"><img src="assets/images/paan_patta_(5 pcs).png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Paan Patta (5 pcs)</h4><div class="price">₹29</div><span class="tag">Fresh daily</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('paan-patta')" aria-label="Add to cart">+</button>
    </div>
    <div class="prod-card">
      <a href="product.php?id=banana-plant" class="prod-link">
        <div class="prod-visual"><img src="assets/images/Banana Plant (Kala Bou).png" alt="Satyanarayan Puja Kit"></div>
        <div class="prod-body"><h4>Banana Plant (Kala Bou)</h4><div class="price">₹149</div><span class="tag">Ritual use</span></div>
      </a>
      <button class="quick-add" onclick="quickAdd('banana-plant')" aria-label="Add to cart">+</button>
    </div>
  </div>
</section>

<section class="block">
  <div class="wa-feature">
    <div>
      <h2>List lambi hai? Bas WhatsApp karein</h2>
      <p>Samagri ki poori list photo ya text mein bhejein, hum arrange karke deliver kar denge.</p>
      <a href="contact.php" class="wa-btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2z"/></svg>
        List bhejein
      </a>
    </div>
    <div class="wa-mock">
      <div class="wa-bubble">Upanayan sanskar ke liye samagri list bhej raha hoon</div>
      <div class="wa-bubble me">Mil gaya! Kal shaam tak ready ho jayega, PAN-India shipping bhi available hai 🙏</div>
    </div>
  </div>
</section>

<footer>
  <div class="foot-grid">
    <div>
      <div class="logo-text" style="margin-bottom:12px;">Shubh<span style="color:#FBD599;">Samagri</span></div>
      <p style="max-width:260px;">Puja samagri aur wedding items — local shehar mein same-day, poore Bharat mein shipping ke saath.</p>
    </div>
    <div>
      <h4>Categories</h4>
      <a href="puja-samagri.php">Puja samagri</a>
      <a href="wedding-items.php">Wedding items</a>
      <a href="services.php">Services</a>
    </div>
    <div>
      <h4>Company</h4>
      <a href="index.php">Home</a>
      <a href="contact.php">Contact</a>
    </div>
    <div>
      <h4>Contact</h4>
      <a href="#">WhatsApp: +91 00000 00000</a>
      <a href="#">hello@shubhsamagri.com</a>
      <a href="#">Local city + Pan-India</a>
    </div>
  </div>
  <div class="foot-bottom">
    <span>© 2026 ShubhSamagri. Demo mockup — sample business hai.</span>
    <span>Made for aapka business</span>
  </div>
</footer>

<div class="added-toast" id="added-toast">Cart mein add ho gaya ✓</div>

<script src="products.js"></script>
<script src="cart.js"></script>
<script>
function quickAdd(id){
  addToCart(id, 1);
  const toast = document.getElementById('added-toast');
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 1800);
}
</script>
</body>
</html>
