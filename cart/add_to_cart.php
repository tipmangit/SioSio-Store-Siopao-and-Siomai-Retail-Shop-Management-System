<?php
session_start();
include("../config.php");

// always return JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Determine if user is logged in or guest
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = isset($_SESSION['valid']) ? $_SESSION['valid'] : null; // Get user's name
$session_id = $user_id ? null : session_id(); // Use session ID for guests

$product = $_POST['product'] ?? '';
$product_id_direct = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;

// image mapping (optional)
$images = [
    "Pork Siomai" => "https://media.istockphoto.com/id/2182583656/photo/chinese-steamed-dumpling-or-shumai-in-japanese-language-meatball-dumpling-with-wanton-skin.jpg?s=612x612&w=0&k=20&c=0K7_ee0dwfAZhcZZajZRSv8uTifXZhG6LVmlKnSe-0U=",
    "Chicken Siomai" => "https://media.istockphoto.com/id/1336438874/photo/delicious-dim-sum-home-made-chinese-dumplings-served-on-plate.jpg?s=612x612&w=0&k=20&c=11KB0bXoZeMrlzaHN2q9aZq8kqtdvp-d4Oggc2TF8M4=",
    "Beef Siomai" => "https://media.istockphoto.com/id/2189370578/photo/delicious-shumai-shumay-siomay-chicken-in-bowl-snack-menu.jpg?s=612x612&w=0&k=20&c=hD4kuZsiGIjgyUPq-seqv229pFE43CnS0Do3EH_2E_Y=",
    "Tuna Siomai" => "https://media.istockphoto.com/id/1084916088/photo/close-up-cooking-homemade-shumai.jpg?s=612x612&w=0&k=20&c=M1RyWV62MACQffBC40UzZ_h-BsXOj4bkaMBrxnbMTzc=",
    "Shark's Fin Siomai" => "https://media.istockphoto.com/id/1330456626/photo/steamed-shark-fin-dumplings-served-with-chili-garlic-oil-and-calamansi.jpg?s=612x612&w=0&k=20&c=9Zi1JmbwvYtIlZJqZb6tHOVC21rS-IbwZXS-IeflE30=",
    "Japanese Siomai" => "https://media.istockphoto.com/id/1221287744/photo/ground-pork-with-crab-stick-wrapped-in-nori.jpg?s=612x612&w=0&k=20&c=Rniq7tdyCqVZHpwngsbzOk1dG1u8pTEeUDE8arsfOUY=",
    "Asado Siopao" => "https://media.istockphoto.com/id/1163708923/photo/hong-kong-style-chicken-char-siew-in-classic-polo-bun-polo-bun-or-is-a-kind-of-crunchy-and.jpg?s=612x612&w=0&k=20&c=R9DC49-UsxYUPlImX6O47LQyafOu1Cp5rNxp3XifFNI=",
    "Bola-Bola Siopao" => "https://media.istockphoto.com/id/1184080523/photo/wanton-noodle-soup-and-siopao.jpg?s=612x612&w=0&k=20&c=oRJanjrTxICQfuzm9bXVPYkw9nKh74tcwjH1cVzXzN8=",
    "Choco Siopao" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxSCl2zlIK85vMZ6nRYuWpqde6JnIxBUTe-w&s",
    "Ube Siopao" => "https://media.istockphoto.com/id/2161276374/photo/vivid-steamed-purple-ube-sweet-potato-dumplings.jpg?s=612x612&w=0&k=20&c=Mb2rl1JZPvG0d5v-_gSC7Mx50DNggFJiTEcoTayqB1Q=",
    "Red Bean Siopao" => "https://media.istockphoto.com/id/1172915611/photo/asian-steamed-bun-with-adzuki-red-bean-paste-filling-or-bakpao.jpg?s=612x612&w=0&k=20&c=hImY86ZyoR8y2FC17yLpkCA5amxrZDxCeuVokJnY5w0=",
    "Custard Siopao" => "https://media.istockphoto.com/id/957584318/photo/chinese-steamed-bun-and-orange-sweet-creamy-lava-on-chinese-pattern-dish.jpg?s=612x612&w=0&k=20&c=5CJuHZdTLVIlN5gq_jmer--RWri-TDliTtQoIvAc97M=",
];

$image = $images[$product] ?? "images/placeholder.jpg";

// Handle both product name and direct product_id
if ($product_id_direct > 0) {
    // Direct product_id provided (from favorites page)
    $stmt = $con->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id_direct);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $product_row = $result->fetch_assoc();
    $product_id = $product_row['id'];
    $product_name = $product_row['name'];
    $price = $product_row['price']; // Use price from database
} else if ($product !== '') {
    // Product name provided (from products page)
    $stmt = $con->prepare("SELECT id, name, price FROM products WHERE name = ?");
    $stmt->bind_param("s", $product);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $product_row = $result->fetch_assoc();
    $product_id = $product_row['id'];
    $product_name = $product_row['name'];
    if ($price <= 0) {
        $price = $product_row['price']; // Use price from database if not provided
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing product information']);
    exit;
}

// Check if item already exists in cart
if ($user_id) {
    // For logged-in users
    $stmt = $con->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND status = 'active'");
    $stmt->bind_param("ii", $user_id, $product_id);
} else {
    // For guest users
    $stmt = $con->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ? AND status = 'active'");
    $stmt->bind_param("si", $session_id, $product_id);
}
$stmt->execute();
$cart_result = $stmt->get_result();

if ($cart_result->num_rows > 0) {
    // Update existing cart item
    $cart_item = $cart_result->fetch_assoc();
    $new_quantity = $cart_item['quantity'] + 1;
    
    $stmt = $con->prepare("UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
    $stmt->execute();
} else {
    // Insert new cart item
    if ($user_id) {
        // For logged-in users - include user name
        $stmt = $con->prepare("INSERT INTO cart (user_id, user_name, product_id, product_name, quantity, price_at_time) VALUES (?, ?, ?, ?, 1, ?)");
        $stmt->bind_param("isisd", $user_id, $user_name, $product_id, $product_name, $price);
    } else {
        // For guest users
        $stmt = $con->prepare("INSERT INTO cart (session_id, product_id, product_name, quantity, price_at_time) VALUES (?, ?, ?, 1, ?)");
        $stmt->bind_param("sisd", $session_id, $product_id, $product_name, $price);
    }
    $stmt->execute();
}

// Get updated cart count
if ($user_id) {
    // For logged-in users
    $stmt = $con->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE user_id = ? AND status = 'active'");
    $stmt->bind_param("i", $user_id);
} else {
    // For guest users
    $stmt = $con->prepare("SELECT SUM(quantity) as cart_count FROM cart WHERE session_id = ? AND status = 'active'");
    $stmt->bind_param("s", $session_id);
}
$stmt->execute();
$count_result = $stmt->get_result();
$cart_count = $count_result->fetch_assoc()['cart_count'] ?? 0;

// return both keys to be robust
echo json_encode([
    'success' => true,
    'cart_count' => $cart_count,
    'cartCount' => $cart_count
]);
exit;
