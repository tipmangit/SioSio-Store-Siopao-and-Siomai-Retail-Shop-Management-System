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
    <link rel="stylesheet" href="cart.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
    <?php include("../headfoot/header.php")   ?>

            <main>
                <main class="cart-page">
            <h2 class="cart-title"> Your Cart</h2>

            <div class="cart-container">
                <!-- Example Cart Item -->
                <div class="cart-item">
                    <div class="cart-img">
                        <img src="https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=" alt="Siopao">
                    </div>
                    <div class="cart-details">
                        <h3 class="cart-item-name">Asado Siopao</h3>
                        <p class="cart-item-desc">Fluffy bun with savory pork asado filling.</p>
                        <div class="cart-actions">
                            <div class="quantity-control">
                                <button class="qty-btn">-</button>
                                <input type="number" class="qty-input" value="1" min="1">
                                <button class="qty-btn">+</button>
                            </div>
                            <p class="cart-price">₱55.00</p>
                            <button class="remove-btn">✖</button>
                        </div>
                    </div>
                </div>

                <!-- Another Example -->
                <div class="cart-item">
                    <div class="cart-img">
                        <img src="https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=" alt="Siomai">
                    </div>
                    <div class="cart-details">
                        <h3 class="cart-item-name">Pork Siomai (4pcs)</h3>
                        <p class="cart-item-desc">Classic pork siomai with dipping sauce.</p>
                        <div class="cart-actions">
                            <div class="quantity-control">
                                <button class="qty-btn">-</button>
                                <input type="number" class="qty-input" value="2" min="1">
                                <button class="qty-btn">+</button>
                            </div>
                            <p class="cart-price">₱80.00</p>
                            <button class="remove-btn">✖</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <p>Subtotal: <span>₱215.00</span></p>
                <p>Delivery Fee: <span>₱50.00</span></p>
                <hr>
                <p class="cart-total">Total: <span>₱265.00</span></p>
                <button class="checkout-btn">Proceed to Checkout</button>
            </div>
            </main>


    <?php include("../headfoot/footer.php")   ?>

    <script src="script.js"></script>
</body>
</html>