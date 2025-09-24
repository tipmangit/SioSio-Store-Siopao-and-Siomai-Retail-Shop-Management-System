<?php
session_start();
include("../config.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT p.* 
        FROM favorites f 
        JOIN products p ON f.product_id = p.id 
        WHERE f.user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$favorites = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Favorites</title>
  <link rel="stylesheet" href="../favorites/favorites.css">
</head>
<body>

<?php include("../headfoot/header.php"); ?>

<main class="favorites-page">
  <h2 class="favorites-title">Your Favorites</h2>
  <div class="favorites-grid">
    <?php if (count($favorites) > 0): ?>
      <?php foreach ($favorites as $item): ?>
        <div class="favorite-item">
          <div class="favorite-img">
            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          </div>
          <div class="favorite-details">
            <h3 class="favorite-name"><?= htmlspecialchars($item['name']) ?></h3>
            <p class="favorite-desc"><?= htmlspecialchars($item['description']) ?></p>
            <button class="add-to-cart-btn" data-product-id="<?= $item['id'] ?>">Add to Cart</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>You have no favorites yet.</p>
    <?php endif; ?>
  </div>
</main>

<?php include("../headfoot/footer.php"); ?>

<script src="../favorites/favorites.js"></script>
</body>
</html>
