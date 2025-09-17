<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);

// Ensure session/cart structure uses 'quantity' (not 'qty')
$cart = $_SESSION['cart'] ?? [];

$subtotal = 0.0;
foreach ($cart as $item) {
    $subtotal += floatval($item['price']) * intval($item['quantity']);
}
$deliveryFee = $subtotal > 0 ? 50 : 0;
$total = $subtotal + $deliveryFee;
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
    <?php include("../headfoot/header.php") ?>

    <main class="cart-page">
        <h2 class="cart-title"> Your Cart</h2>
        <div class="cart-container">
            <?php if (empty($cart)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($cart as $name => $item): ?>
                    <div class="cart-item"
                         data-name="<?= htmlspecialchars($name) ?>"
                         data-price="<?= htmlspecialchars($item['price']) ?>">
                        <div class="cart-img">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($name) ?>">

                        </div>
                        <div class="cart-details">
                            <h3 class="cart-item-name"><?= htmlspecialchars($name) ?></h3>
                            <p class="cart-item-desc"><?= htmlspecialchars($item['name'] ?? '') ?></p>

                            <div class="cart-actions">
                                <div class="quantity-control">
                                    <button class="qty-btn minus">-</button>
                                    <input type="number" class="qty-input" value="<?= intval($item['quantity']) ?>" min="1">
                                    <button class="qty-btn plus">+</button>
                                </div>

                                <p class="cart-price">₱<?= number_format(floatval($item['price']) * intval($item['quantity']), 2) ?></p>

                                <button class="remove-btn" title="Remove item">✖</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <h3>Order Summary</h3>
            <p>Subtotal: <span id="subtotal">₱<?= number_format($subtotal, 2) ?></span></p>
            <p>Delivery Fee: <span id="delivery">₱<?= number_format($deliveryFee, 2) ?></span></p>
            <hr>
            <p class="cart-total">Total: <span id="total">₱<?= number_format($total, 2) ?></span></p>
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>
    </main>

    <?php include("../headfoot/footer.php") ?>

    <script src="cart.js"></script>
</body>
</html>
