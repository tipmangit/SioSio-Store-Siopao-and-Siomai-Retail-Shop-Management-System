<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$product = $_POST['product'] ?? '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;
$remove = (isset($_POST['remove']) && $_POST['remove'] == 1) ? true : false;

// if no cart yet, nothing to update
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($product === '' || !isset($_SESSION['cart'][$product])) {
    // compute totals anyway for response
    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity') ?: [0]);
    $subtotal = 0.0;
    foreach ($_SESSION['cart'] as $it) {
        $subtotal += floatval($it['price']) * intval($it['quantity']);
    }
    echo json_encode(['success' => false, 'message' => 'Product not in cart', 'cartCount' => $cartCount, 'subtotal' => $subtotal]);
    exit;
}

if ($remove || ($quantity !== null && $quantity <= 0)) {
    unset($_SESSION['cart'][$product]);
} else {
    // clamp quantity to at least 1
    $_SESSION['cart'][$product]['quantity'] = max(1, intval($quantity));
}

// recompute totals
$cartCount = 0;
$subtotal = 0.0;
foreach ($_SESSION['cart'] as $it) {
    $cartCount += intval($it['quantity']);
    $subtotal += floatval($it['price']) * intval($it['quantity']);
}

echo json_encode([
    'success'   => true,
    'cartCount' => $cartCount,
    'subtotal'  => $subtotal
]);
exit;
