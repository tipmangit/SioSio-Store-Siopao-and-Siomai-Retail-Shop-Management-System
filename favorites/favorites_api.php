<?php
session_start();
include("../config.php");
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// If GET: return the list of favorite product IDs
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $con->prepare("SELECT product_id FROM favorites WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = (int)$row['product_id'];
    }
    echo json_encode(['success' => true, 'favorites' => $ids]);
    exit;
}

// If POST: add or remove a favorite
if (!isset($_POST['action'], $_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $_POST['action'];
$product_id = (int)$_POST['product_id'];

if ($action === 'add') {
    $stmt = $con->prepare("INSERT INTO favorites (user_id, product_id)
                            VALUES (?, ?)
                            ON DUPLICATE KEY UPDATE product_id = product_id");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Added to favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add to favorites']);
    }
    exit;
}

if ($action === 'remove') {
    $stmt = $con->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Removed from favorites', 'affected_rows' => $stmt->affected_rows]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $con->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action']);
?>
