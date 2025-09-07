<?php
include("../config.php");
session_start();

$isLoggedin = isset($_SESSION['valid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SioSio</title>
    <link rel="stylesheet" href="Products.css">
    <link rel="stylesheet" href="Products.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-left">
                    <a href="../homepage/index.php" class="nav-link">Home</a>
                    <a href="../products/product.php" class="nav-link">Products</a>
                </div>
                <div class="nav-center">
                    <h1 class="logo">
                        Welcome, mga ka-<span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span>!
                    </h1>
                </div>
                <div class="nav-right">
                    <div class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Menu ▼</a>
                        <div class="dropdown-menu">
                            <a href="#siomai-section" class="dropdown-item"><span class="sio-highlight">Sio</span>mai</a>
                            <a href="#siopao-section" class="dropdown-item"><span class="sio-highlight">Sio</span>pao</a>
                        </div>
                    </div>
                    <a href="../homepage/index.php" class="nav-link franchise-btn">Back To Home</a>
                    <div class="dropdown account-dropdown">
                        <a href="#" class="nav-link account-btn">
                            <span class="account-icon">👤</span>
                        </a>
                        <div class="dropdown-menu account-menu">
                            <?php if($isLoggedin): ?>
                                <a href="../logout.php" class="dropdown-item">Log Out</a>
                                <a href="#" class="dropdown-item">My Orders</a>
                                <a href="#" class="dropdown-item">Profile</a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item">Help & Support</a>
                            <?php else: ?>
                                <a href="../loginreg/logreg.php" class="dropdown-item">Sign In</a>
                                <a href="#" class="dropdown-item">Create Account</a>
                                <a href="#" class="dropdown-item">My Orders</a>
                                <a href="#" class="dropdown-item">Profile</a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item">Help & Support</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($isLoggedin): ?>
                         <span class="welcome-message">
                            Welcome, <?php echo htmlspecialchars($_SESSION['valid']); ?>
                        </span>
                    <?php endif; ?>
                    <a href="../cart/cart.php" class="nav-link cart-link">
                        <!-- Unicode cart icon for visibility -->
                        &#128722;
                    </a>
                         <!-- Search -->
                    <input type="text" id="nav-search-input" class="nav-search-input" placeholder="Search...">
                    <button type="button" class="nav-link nav-search-btn" id="nav-search-btn">Search</button>
                </div>
            </div>
        </nav>
    </header>

   <main style="padding-top: 70px;">
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Our <span class="sio-highlight">Sio</span>mai & <span class="sio-highlight">Sio</span>pao Products</h1>
                <p class="page-subtitle">Choose from our delicious selection of authentic Filipino favorites</p>
            </div>
        </section>
        
        <!-- Sorting Controls -->
        <div class="sorting-container">
            <div class="container" style="display: flex; justify-content: center; align-items: center; margin: 30px auto; padding: 20px; background: #f8f9fa; border-radius: 10px; max-width: 400px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <label for="price-sort" style="font-weight: 600; color: #333; margin-right: 15px;">Sort by Price:</label>
                <select id="price-sort" style="margin-right: 15px; border-radius: 5px; padding: 0.5rem 1rem; border: 1px solid #ddd; font-size: 1rem;">
                    <option value="min-max">Low to High</option>
                    <option value="max-min">High to Low</option>
                </select>
                <button id="sort-price-btn" class="nav-link" style="margin: 0;">Sort</button>
            </div>
        </div>

          <section id="siomai-section" class="flavors">
            <div class="container">
                <h2 class="section-title"><span class="sio-highlight">Sio</span>mai Flavors</h2>
                <div class="flavors-grid">
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=" alt="Pork & Shrimp Siomai">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Pork <span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-price">₱25.00</p>
                            <p class="product-description">Juicy pork siomai with authentic Filipino flavors</p>
                            <button class="add-to-cart-btn" data-product="Pork Siomai" data-price="25.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1336438874/photo/delicious-dim-sum-home-made-chinese-dumplings-served-on-plate.jpg?s=612x612&w=0&k=20&c=11KB0bXoZeMrlzaHN2q9aZq8kqtdvp-d4Oggc2TF8M4=" alt="Chicken Siomai">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Chicken <span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-price">₱22.00</p>
                            <p class="product-description">Tender chicken siomai with fresh ingredients</p>
                            <button class="add-to-cart-btn" data-product="Chicken Siomai" data-price="22.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/2189370578/photo/delicious-shumai-shumay-siomay-chicken-in-bowl-snack-menu.jpg?s=612x612&w=0&k=20&c=hD4kuZsiGIjgyUPq-seqv229pFE43CnS0Do3EH_2E_Y=" alt="Beef Siomai">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Beef <span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-price">₱28.00</p>
                            <p class="product-description">Premium beef siomai with rich savory taste</p>
                            <button class="add-to-cart-btn" data-product="Beef Siomai" data-price="28.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1084916088/photo/close-up-cooking-homemade-shumai.jpg?s=612x612&w=0&k=20&c=M1RyWV62MACQffBC40UzZ_h-BsXOj4bkaMBrxnbMTzc=" alt="Tuna Siomai">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Tuna <span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-price">₱30.00</p>
                            <p class="product-description">Fresh tuna siomai with ocean-fresh flavor</p>
                            <button class="add-to-cart-btn" data-product="Tuna Siomai" data-price="30.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1330456626/photo/steamed-shark-fin-dumplings-served-with-chili-garlic-oil-and-calamansi.jpg?s=612x612&w=0&k=20&c=9Zi1JmbwvYtIlZJqZb6tHOVC21rS-IbwZXS-IeflE30=" alt="Shark's Fin Siomai">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Shark's Fin <span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-price">₱35.00</p>
                            <p class="product-description">Premium shark's fin siomai with delicate texture</p>
                            <button class="add-to-cart-btn" data-product="Shark's Fin Siomai" data-price="35.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1221287744/photo/ground-pork-with-crab-stick-wrapped-in-nori.jpg?s=612x612&w=0&k=20&c=Rniq7tdyCqVZHpwngsbzOk1dG1u8pTEeUDE8arsfOUY=" alt="Japanese Siomai">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Japanese <span class="sio-highlight">Sio</span>mai</h3>
                            <p class="product-price">₱32.00</p>
                            <p class="product-description">Japanese-style siomai with nori wrapping</p>
                            <button class="add-to-cart-btn" data-product="Japanese Siomai" data-price="32.00">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="siopao-section" class="siopao-section">
            <div class="container">
                <h2 class="section-title"><span class="sio-highlight">Sio</span>pao Flavors</h2>
                <div class="flavors-grid">
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=" alt="Asado Siopao">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Asado <span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-price">₱45.00</p>
                            <p class="product-description">Classic asado siopao with sweet-savory pork filling</p>
                            <button class="add-to-cart-btn" data-product="Asado Siopao" data-price="45.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1184080523/photo/wanton-noodle-soup-and-siopao.jpg?s=612x612&w=0&k=20&c=oRJanjrTxICQfuzm9bXVPYkw9nKh74tcwjH1cVzXzN8=" alt="Bola-Bola Siopao">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Bola-Bola <span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-price">₱42.00</p>
                            <p class="product-description">Hearty bola-bola siopao with meatball filling</p>
                            <button class="add-to-cart-btn" data-product="Bola-Bola Siopao" data-price="42.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxSCl2zlIK85vMZ6nRYuWpqde6JnIxBUTe-w&s" alt="Choco Siopao">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Choco <span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-price">₱38.00</p>
                            <p class="product-description">Sweet chocolate-filled siopao for dessert lovers</p>
                            <button class="add-to-cart-btn" data-product="Choco Siopao" data-price="38.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/2161276374/photo/vivid-steamed-purple-ube-sweet-potato-dumplings.jpg?s=612x612&w=0&k=20&c=Mb2rl1JZPvG0d5v-_gSC7Mx50DNggFJiTEcoTayqB1Q=" alt="Ube Siopao">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Ube <span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-price">₱40.00</p>
                            <p class="product-description">Filipino ube-flavored siopao with purple yam</p>
                            <button class="add-to-cart-btn" data-product="Ube Siopao" data-price="40.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/1172915611/photo/asian-steamed-bun-with-adzuki-red-bean-paste-filling-or-bakpao.jpg?s=612x612&w=0&k=20&c=hImY86ZyoR8y2FC17yLpkCA5amxrZDxCeuVokJnY5w0=" alt="Red Bean Siopao">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Red Bean <span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-price">₱36.00</p>
                            <p class="product-description">Traditional red bean paste siopao with sweet flavor</p>
                            <button class="add-to-cart-btn" data-product="Red Bean Siopao" data-price="36.00">Add to Cart</button>
                        </div>
                    </div>
                    <div class="product-item">
                        <div class="flavor-image">
                            <img src="https://media.istockphoto.com/id/957584318/photo/chinese-steamed-bun-and-orange-sweet-creamy-lava-on-chinese-pattern-dish.jpg?s=612x612&w=0&k=20&c=5CJuHZdTLVIlN5gq_jmer--RWri-TDliTtQoIvAc97M=" alt="Custard Siopao">
                        </div>
                        <div class="product-info">
                            <h3 class="flavor-title">Custard <span class="sio-highlight">Sio</span>pao</h3>
                            <p class="product-price">₱44.00</p>
                            <p class="product-description">Creamy custard-filled siopao with rich texture</p>
                            <button class="add-to-cart-btn" data-product="Custard Siopao" data-price="44.00">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo-section">
                    <div class="footer-logo">
                        <img src="../images/siosiologo.png" alt="SioSio Logo" class="logo-img">
                    </div>
                    <p class="footer-copyright">©2025 <span class="sio-highlight">Sio</span><span class="sio-highlight">Sio</span></p>
                </div>
                
                <div class="footer-links">
                    <div class="footer-column">
                        <h3 class="footer-title">Quick Links</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">Home</a></li>
                            <li><a href="#" class="footer-link">Products</a></li>
                            <li><a href="#" class="footer-link">Our Company</a></li>
                            <li><a href="#" class="footer-link">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3 class="footer-title">Menu</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link"> <span class="sio-highlight">Sio</span>mai</a></li>
                            <li><a href="#" class="footer-link"><span class="sio-highlight">Sio</span>pao</a></li>
                        </ul>
                    </div>
                    
                    <!-- <div class="footer-column">
                        <h3 class="footer-title">Brands</h3>
                        <ul class="footer-list">
                            <li><a href="#" class="footer-link">GreaTaste <span class="sio-highlight">Sio</span>mai</a></li>
                            <li><a href="#" class="footer-link">Master Choice</a></li>
                            <li><a href="#" class="footer-link">Master <span class="sio-highlight">Sio</span>pao</a></li>
                        </ul>
                    </div> -->
                    
                    <div class="footer-column">
                        <h3 class="footer-title">Follow us:</h3>
                        <div class="social-links">
                            <a href="" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                                <span class="social-icon">f</span>
                            </a>
                            <a href="" class="social-link instagram" target="_blank" rel="noopener noreferrer">
                                <span class="social-icon">📷</span>
                            </a>
                            <a href="" class="social-link twitter" target="_blank" rel="noopener noreferrer">
                                <span class="social-icon">𝕏</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-legal">
                    <a href="#" class="legal-link">Terms of Use</a>
                    <a href="#" class="legal-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="product.js"></script>
</body>
</html>
