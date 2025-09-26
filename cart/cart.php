<?php
include("../config.php");
$isLoggedin = isset($_SESSION['valid']);

// Get cart items from database for both logged-in users and guests
$cart = [];
$subtotal = 0.0;

// Determine if user is logged in or guest
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = $user_id ? null : session_id();

if ($user_id || $session_id) {
    // Get cart items with product details
    if ($user_id) {
        // For logged-in users
        $stmt = $con->prepare("
            SELECT c.id as cart_id, c.quantity, c.price_at_time, c.total_price,
                   c.product_id, c.product_name, p.description, p.image_url
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ? AND c.status = 'active'
            ORDER BY c.created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
    } else {
        // For guest users
        $stmt = $con->prepare("
            SELECT c.id as cart_id, c.quantity, c.price_at_time, c.total_price,
                   c.product_id, c.product_name, p.description, p.image_url
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.session_id = ? AND c.status = 'active'
            ORDER BY c.created_at DESC
        ");
        $stmt->bind_param("s", $session_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart[$row['product_name']] = [
            'cart_id' => $row['cart_id'],
            'product_id' => $row['product_id'],
            'name' => $row['product_name'],
            'price' => $row['price_at_time'],
            'quantity' => $row['quantity'],
            'image' => $row['image_url'],
            'description' => $row['description']
        ];
        $subtotal += floatval($row['total_price']);
    }
}

$deliveryFee = $subtotal > 0 ? 50 : 0;
$total = $subtotal + $deliveryFee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - SioSio</title>
    
    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
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
                         data-cart-id="<?= htmlspecialchars($item['cart_id']) ?>"
                         data-product-id="<?= htmlspecialchars($item['product_id']) ?>"
                         data-name="<?= htmlspecialchars($name) ?>"
                         data-price="<?= htmlspecialchars($item['price']) ?>">
                        <div class="cart-img">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($name) ?>">

                        </div>
                        <div class="cart-details">
                            <h3 class="cart-item-name"><?= htmlspecialchars($name) ?></h3>
                            <p class="cart-item-desc"><?= htmlspecialchars($item['description'] ?? '') ?></p>

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

    <!-- Bootstrap 5.3.2 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script src="cart.js"></script>
</body>
</html>
