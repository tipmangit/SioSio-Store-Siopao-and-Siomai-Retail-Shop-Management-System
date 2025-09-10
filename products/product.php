<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SioSio</title>
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom Bootstrap Styles -->
    <link rel="stylesheet" href="bootstrap-custom.css">
    <link rel="stylesheet" href="custom.css">
    <!-- Original Custom CSS (loaded after Bootstrap to override if needed) -->
    <link rel="stylesheet" href="Products.css">
    <link rel="stylesheet" href="Products.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
    <?php include("../headfoot/header.php")   ?>

        <!-- Page Header -->
        <section class="page-header bg-dark text-white py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1 class="display-4 fw-bold mb-3">Our <span class="sio-highlight">Sio</span>mai & <span class="sio-highlight">Sio</span>pao Products</h1>
                        <p class="lead">Choose from our delicious selection of authentic Filipino favorites</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Sorting Controls -->
        <section class="py-4 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <label for="price-sort" class="form-label fw-semibold mb-0">Sort by Price:</label>
                                    </div>
                                    <div class="col position-relative">
                                        <select id="price-sort" class="form-select pe-5">
                                            <option value="min-max">Low to High</option>
                                            <option value="max-min">High to Low</option>
                                        </select>
                                        <i class="bi bi-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 text-primary fs-5" style="pointer-events: none;"></i>
                                    </div>
                                    <div class="col-auto">
                                        <button id="sort-price-btn" class="btn btn-primary">Sort</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

          <!-- Siomai Section -->
          <section id="siomai-section" class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center mb-5 display-5 fw-bold"><span class="sio-highlight">Sio</span>mai Flavors</h2>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=" alt="Pork & Shrimp Siomai" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Pork <span class="sio-highlight">Sio</span>mai</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱25.00</p>
                                <p class="card-text text-muted">Juicy pork siomai with authentic Filipino flavors</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Pork Siomai" data-price="25.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1336438874/photo/delicious-dim-sum-home-made-chinese-dumplings-served-on-plate.jpg?s=612x612&w=0&k=20&c=11KB0bXoZeMrlzaHN2q9aZq8kqtdvp-d4Oggc2TF8M4=" alt="Chicken Siomai" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Chicken <span class="sio-highlight">Sio</span>mai</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱22.00</p>
                                <p class="card-text text-muted">Tender chicken siomai with fresh ingredients</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Chicken Siomai" data-price="22.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/2189370578/photo/delicious-shumai-shumay-siomay-chicken-in-bowl-snack-menu.jpg?s=612x612&w=0&k=20&c=hD4kuZsiGIjgyUPq-seqv229pFE43CnS0Do3EH_2E_Y=" alt="Beef Siomai" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Beef <span class="sio-highlight">Sio</span>mai</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱28.00</p>
                                <p class="card-text text-muted">Premium beef siomai with rich savory taste</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Beef Siomai" data-price="28.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1084916088/photo/close-up-cooking-homemade-shumai.jpg?s=612x612&w=0&k=20&c=M1RyWV62MACQffBC40UzZ_h-BsXOj4bkaMBrxnbMTzc=" alt="Tuna Siomai" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Tuna <span class="sio-highlight">Sio</span>mai</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱30.00</p>
                                <p class="card-text text-muted">Fresh tuna siomai with ocean-fresh flavor</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Tuna Siomai" data-price="30.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1330456626/photo/steamed-shark-fin-dumplings-served-with-chili-garlic-oil-and-calamansi.jpg?s=612x612&w=0&k=20&c=9Zi1JmbwvYtIlZJqZb6tHOVC21rS-IbwZXS-IeflE30=" alt="Shark's Fin Siomai" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Shark's Fin <span class="sio-highlight">Sio</span>mai</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱35.00</p>
                                <p class="card-text text-muted">Premium shark's fin siomai with delicate texture</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Shark's Fin Siomai" data-price="35.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1221287744/photo/ground-pork-with-crab-stick-wrapped-in-nori.jpg?s=612x612&w=0&k=20&c=Rniq7tdyCqVZHpwngsbzOk1dG1u8pTEeUDE8arsfOUY=" alt="Japanese Siomai" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Japanese <span class="sio-highlight">Sio</span>mai</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱32.00</p>
                                <p class="card-text text-muted">Japanese-style siomai with nori wrapping</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Japanese Siomai" data-price="32.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Siopao Section -->
        <section id="siopao-section" class="py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center mb-5 display-5 fw-bold"><span class="sio-highlight">Sio</span>pao Flavors</h2>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=" alt="Asado Siopao" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Asado <span class="sio-highlight">Sio</span>pao</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱45.00</p>
                                <p class="card-text text-muted">Classic asado siopao with sweet-savory pork filling</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Asado Siopao" data-price="45.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1184080523/photo/wanton-noodle-soup-and-siopao.jpg?s=612x612&w=0&k=20&c=oRJanjrTxICQfuzm9bXVPYkw9nKh74tcwjH1cVzXzN8=" alt="Bola-Bola Siopao" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Bola-Bola <span class="sio-highlight">Sio</span>pao</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱42.00</p>
                                <p class="card-text text-muted">Hearty bola-bola siopao with meatball filling</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Bola-Bola Siopao" data-price="42.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxSCl2zlIK85vMZ6nRYuWpqde6JnIxBUTe-w&s" alt="Choco Siopao" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Choco <span class="sio-highlight">Sio</span>pao</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱38.00</p>
                                <p class="card-text text-muted">Sweet chocolate-filled siopao for dessert lovers</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Choco Siopao" data-price="38.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/2161276374/photo/vivid-steamed-purple-ube-sweet-potato-dumplings.jpg?s=612x612&w=0&k=20&c=Mb2rl1JZPvG0d5v-_gSC7Mx50DNggFJiTEcoTayqB1Q=" alt="Ube Siopao" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Ube <span class="sio-highlight">Sio</span>pao</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱40.00</p>
                                <p class="card-text text-muted">Filipino ube-flavored siopao with purple yam</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Ube Siopao" data-price="40.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/1172915611/photo/asian-steamed-bun-with-adzuki-red-bean-paste-filling-or-bakpao.jpg?s=612x612&w=0&k=20&c=hImY86ZyoR8y2FC17yLpkCA5amxrZDxCeuVokJnY5w0=" alt="Red Bean Siopao" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Red Bean <span class="sio-highlight">Sio</span>pao</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱36.00</p>
                                <p class="card-text text-muted">Traditional red bean paste siopao with sweet flavor</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Red Bean Siopao" data-price="36.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card h-100 shadow-sm product-item">
                            <div class="card-img-top overflow-hidden" style="height: 200px;">
                                <img src="https://media.istockphoto.com/id/957584318/photo/chinese-steamed-bun-and-orange-sweet-creamy-lava-on-chinese-pattern-dish.jpg?s=612x612&w=0&k=20&c=5CJuHZdTLVIlN5gq_jmer--RWri-TDliTtQoIvAc97M=" alt="Custard Siopao" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Custard <span class="sio-highlight">Sio</span>pao</h5>
                                <p class="text-primary fw-bold fs-5 mb-2">₱44.00</p>
                                <p class="card-text text-muted">Creamy custard-filled siopao with rich texture</p>
                                <button class="btn btn-primary mt-auto add-to-cart-btn" data-product="Custard Siopao" data-price="44.00">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include("../headfoot/footer.php")   ?>

    <!-- Bootstrap 5.3.2 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="product.js"></script>
</body>
</html>
