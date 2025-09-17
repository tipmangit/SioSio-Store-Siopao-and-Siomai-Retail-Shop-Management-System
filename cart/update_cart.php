<?php
session_start();
include("../config.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Determine if user is logged in or guest
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = $user_id ? null : session_id();

$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : null;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;
$remove = (isset($_POST['remove']) && $_POST['remove'] == 1) ? true : false;

if ($cart_id === null) {
    echo json_encode(['success' => false, 'message' => 'Cart ID required']);
    exit;
}

// Verify the cart item belongs to the user or session
if ($user_id) {
    // For logged-in users
    $stmt = $con->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ? AND status = 'active'");
    $stmt->bind_param("ii", $cart_id, $user_id);
} else {
    // For guest users
    $stmt = $con->prepare("SELECT id FROM cart WHERE id = ? AND session_id = ? AND status = 'active'");
    $stmt->bind_param("is", $cart_id, $session_id);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    exit;
}

if ($remove || ($quantity !== null && $quantity <= 0)) {
    // Remove item from cart
    if ($user_id) {
        $stmt = $con->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
    } else {
        $stmt = $con->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
        $stmt->bind_param("is", $cart_id, $session_id);
    }
    $stmt->execute();
} else {
    // Update quantity
    $quantity = max(1, intval($quantity));
    if ($user_id) {
        $stmt = $con->prepare("UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    } else {
        $stmt = $con->prepare("UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND session_id = ?");
        $stmt->bind_param("iis", $quantity, $cart_id, $session_id);
    }
    $stmt->execute();
}

// Get updated cart totals
if ($user_id) {
    // For logged-in users
    $stmt = $con->prepare("
        SELECT SUM(quantity) as cart_count, SUM(total_price) as subtotal
        FROM cart 
        WHERE user_id = ? AND status = 'active'
    ");
    $stmt->bind_param("i", $user_id);
} else {
    // For guest users
    $stmt = $con->prepare("
        SELECT SUM(quantity) as cart_count, SUM(total_price) as subtotal
        FROM cart 
        WHERE session_id = ? AND status = 'active'
    ");
    $stmt->bind_param("s", $session_id);
}
$stmt->execute();
$totals = $stmt->get_result()->fetch_assoc();

$cartCount = $totals['cart_count'] ?? 0;
$subtotal = $totals['subtotal'] ?? 0.0;

echo json_encode([
    'success'   => true,
    'cartCount' => $cartCount,
    'subtotal'  => $subtotal
]);
exit;
