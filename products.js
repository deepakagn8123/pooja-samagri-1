// products.js — central product database used by category pages, product.html and cart.html
const PRODUCTS = [
  // ---------- Puja Samagri & Kits ----------
  {
    id: "satyanarayan-kit", name: "Satyanarayan Puja Kit", category: "Puja Samagri",
    price: 509, oldPrice: 599, unit: "", tag: "Complete kit", badge: "15% OFF",
    color: "#8B1E3F", shape: "kit",
    description: "Satyanarayan puja ke liye zaroori har cheez ek hi box mein — supari, elaichi, laung, kapoor, roli, chawal, aur poori vidhi ki booklet ke saath. Ghar par bina kisi aur saman ke poori puja karne ke liye taiyar.",
    features: ["25+ items ek kit mein", "Vidhi booklet included", "Fresh, shuddh samagri", "Same-day dispatch"]
  },
  {
    id: "ganesh-chaturthi-kit", name: "Ganesh Chaturthi Kit", category: "Puja Samagri",
    price: 404, oldPrice: 449, unit: "", tag: "Festival special", badge: "10% OFF",
    color: "#E8890C", shape: "round",
    description: "Ganesh Chaturthi ke liye special kit — modak samagri, durva, sindoor, aur agarbatti ke saath. Festival season mein sabse zyada bikne wala kit.",
    features: ["Modak samagri included", "Durva aur sindoor", "Festival-special agarbatti", "Limited period offer"]
  },
  {
    id: "durva-sindoor-set", name: "Durva & Sindoor Set", category: "Puja Samagri",
    price: 129, oldPrice: null, unit: "", tag: "Everyday essential", badge: null,
    color: "#B8860B", shape: "rect",
    description: "Roz ki puja ke liye taaza durva aur shuddh sindoor ka combo. Ganesh puja aur roz ke anushthan dono ke liye upyukt.",
    features: ["Taaza durva", "Shuddh sindoor", "Roz ke use ke liye", "Chhoti packaging"]
  },
  {
    id: "agarbatti-dhoop-combo", name: "Agarbatti & Dhoop Combo", category: "Puja Samagri",
    price: 199, oldPrice: 249, unit: "", tag: "Daily use", badge: "20% OFF",
    color: "#5C7A4A", shape: "stick",
    description: "Sandalwood aur rose fragrance mein agarbatti aur dhoop ka combo pack. Lambi lasting fragrance, roz ki puja ke liye best.",
    features: ["2 fragrances included", "Long-lasting", "Low smoke formula", "50 sticks + 20 dhoop cones"]
  },
  // ---------- Idols & Metal Articles ----------
  {
    id: "brass-ganesh-idol", name: "Brass Ganesh Idol", category: "Idols & Metal",
    price: 899, oldPrice: null, unit: "", tag: "6 inch", badge: null,
    color: "#B8860B", shape: "idol",
    description: "Haathon se banaya gaya 6 inch peetal ka Ganesh idol. Ghar ke mandir ya office desk dono ke liye upyukt, fine detailing ke saath.",
    features: ["100% pure brass", "Hand-finished detailing", "6 inch height", "Gift-ready packaging"]
  },
  {
    id: "brass-diya-pair", name: "Brass Diya (Pair)", category: "Idols & Metal",
    price: 249, oldPrice: null, unit: "", tag: "Handcrafted", badge: null,
    color: "#E8890C", shape: "diya",
    description: "Do peetal diyon ka jodaa, roz ki aarti aur tyohaar dono ke liye. Traditional design, sturdy base jo tel/ghee ke saath stable rehta hai.",
    features: ["Pair of 2 diyas", "Traditional design", "Stable wide base", "Easy to clean"]
  },
  {
    id: "panchpradeep", name: "Panchpradeep", category: "Idols & Metal",
    price: 649, oldPrice: null, unit: "", tag: "5-wick brass lamp", badge: null,
    color: "#B8860B", shape: "lamp",
    description: "5 baatiyon wala peetal ka panchpradeep, vishesh anushthan aur aarti ke liye. Bhaari base jo aarti ke dauraan stability deta hai.",
    features: ["5 wicks", "Heavy brass base", "Special occasion use", "Traditional craftsmanship"]
  },
  {
    id: "copper-aachmani", name: "Copper Aachmani-Panchpatra", category: "Idols & Metal",
    price: 399, oldPrice: null, unit: "", tag: "Pure copper", badge: null,
    color: "#B8860B", shape: "vessel",
    description: "Shuddh tamba ka aachmani-panchpatra set, jal shuddhikaran aur anushthan ke liye zaroori. Ayurvedic gunon ke saath copper ka labh bhi milta hai.",
    features: ["100% pure copper", "Aachmani + panchpatra set", "Ritual essential", "Health benefits of copper"]
  },
  // ---------- Fresh Flowers ----------
  {
    id: "genda-mala", name: "Genda Phool Mala", category: "Fresh Flowers",
    price: 49, oldPrice: null, unit: "/day", tag: "Subscription", badge: null,
    color: "#E8890C", shape: "flower",
    description: "Roz taaza genda ki mala, subah delivery ke saath. Subscription lene par har din bina order kiye ghar par mala pahunch jayegi.",
    features: ["Daily fresh delivery", "Subscription available", "Morning delivery slot", "Cancel anytime"]
  },
  {
    id: "tulsi-patta", name: "Tulsi Patta", category: "Fresh Flowers",
    price: 19, oldPrice: null, unit: "/pack", tag: "Fresh cut", badge: null,
    color: "#5C7A4A", shape: "leaf",
    description: "Taaza kate hue tulsi patte, roz ki puja ke liye. Har pack mein 20-25 patte, same-day cutting.",
    features: ["20-25 leaves per pack", "Cut same day", "Pesticide-free", "Small pack size"]
  },
  {
    id: "paan-patta", name: "Paan Patta (5 pcs)", category: "Fresh Flowers",
    price: 29, oldPrice: null, unit: "", tag: "Fresh daily", badge: null,
    color: "#5C7A4A", shape: "leaf",
    description: "5 taaza paan ke patte, puja aur anushthan ke liye. Daily fresh stock se select kiye jaate hain.",
    features: ["5 pieces per pack", "Fresh daily stock", "Ritual use", "Quick delivery"]
  },
  {
    id: "banana-plant", name: "Banana Plant (Kala Bou)", category: "Fresh Flowers",
    price: 149, oldPrice: null, unit: "", tag: "Ritual use", badge: null,
    color: "#5C7A4A", shape: "plant",
    description: "Chhota banana plant, Bengali Durga puja ke Kala Bou anushthan ke liye zaroori. Fresh aur healthy plant, delivery se pehle select kiya jaata hai.",
    features: ["Fresh healthy plant", "Kala Bou ritual essential", "Careful packaging", "Local delivery only"]
  },
  // ---------- Wedding Items ----------
  {
    id: "topor-mukut", name: "Topor-Mukut", category: "Wedding Items",
    price: 799, oldPrice: null, unit: "", tag: "Bengali wedding", badge: null,
    color: "#8B1E3F", shape: "crown",
    description: "Bengali dulha-dulhan ke liye traditional Topor aur Mukut set. Shola craft se haathon se banaya gaya, authentic Bengali wedding ke liye zaroori.",
    features: ["Handmade shola craft", "Bride + groom set", "Authentic Bengali design", "Book 1 week in advance"]
  },
  {
    id: "gachkouto", name: "Gachkouto (Sindoor Box)", category: "Wedding Items",
    price: 539, oldPrice: 599, unit: "", tag: "Bengali wedding", badge: "10% OFF",
    color: "#E8890C", shape: "box",
    description: "Bengali wedding ka traditional sindoor box, khoobsurat carving ke saath. Sindoor daan ceremony ke liye zaroori item.",
    features: ["Traditional carving", "Ceremony essential", "Premium finish", "Gift-ready"]
  },
  {
    id: "maur-patmauri", name: "Maur (Patmauri)", category: "Wedding Items",
    price: 899, oldPrice: null, unit: "", tag: "Bihari wedding", badge: null,
    color: "#B8860B", shape: "round",
    description: "Bihari dulha ke liye traditional Maur (Patmauri), haathon se banaya gaya authentic design. Wedding ki shobha badhata hai.",
    features: ["Handcrafted design", "Authentic Bihari style", "Lightweight", "Book in advance"]
  },
  {
    id: "dala-daura", name: "Dala Daura Set", category: "Wedding Items",
    price: 1299, oldPrice: null, unit: "", tag: "Bihari wedding", badge: null,
    color: "#5C7A4A", shape: "basket",
    description: "Bihari wedding rituals ke liye complete Dala Daura set — decorated baskets jo gift-giving ceremonies mein use hote hain.",
    features: ["Complete ritual set", "Decorated baskets", "Traditional design", "Full set included"]
  },
  {
    id: "bandhanwar", name: "Bandhanwar (Door Toran)", category: "Wedding Items",
    price: 399, oldPrice: null, unit: "", tag: "Marwadi wedding", badge: null,
    color: "#B8860B", shape: "toran",
    description: "Marwadi wedding ke liye traditional door toran/bandhanwar, marigold aur mango leaves ke design mein.",
    features: ["Door decoration", "Traditional motifs", "Easy to hang", "Festive design"]
  },
  {
    id: "kalash-set", name: "Kalash Set", category: "Wedding Items",
    price: 791, oldPrice: 899, unit: "", tag: "Marwadi wedding", badge: "12% OFF",
    color: "#E8890C", shape: "vessel",
    description: "Traditional Kalash set Marwadi wedding rituals ke liye — coconut, mango leaves stand ke saath, poori ceremony ke liye taiyar.",
    features: ["Complete kalash set", "Ritual-ready", "Premium metal finish", "Wedding essential"]
  },
  {
    id: "saat-phere-thali", name: "Saat Phere Thali Set", category: "Wedding Items",
    price: 1499, oldPrice: null, unit: "", tag: "All traditions", badge: null,
    color: "#8B1E3F", shape: "thali",
    description: "Saat phere ceremony ke liye complete thali set — sabhi zaroori items ke saath, har tradition ke liye upyukt.",
    features: ["Complete ceremony set", "Works for all traditions", "Premium thali", "All items included"]
  },
  {
    id: "mandap-flowers", name: "Wedding Mandap Flowers", category: "Wedding Items",
    price: 2999, oldPrice: null, unit: "", tag: "Full decor", badge: null,
    color: "#E8890C", shape: "flower",
    description: "Poore mandap ke liye fresh flower decoration — marigold, roses aur custom themes available. Quote size aur design ke hisaab se badalta hai.",
    features: ["Full mandap coverage", "Custom themes available", "Fresh flowers only", "Site visit included"]
  }
];

function getProduct(id){
  return PRODUCTS.find(p => p.id === id);
}
function getRelated(product, limit=4){
  return PRODUCTS.filter(p => p.category === product.category && p.id !== product.id).slice(0, limit);
}
