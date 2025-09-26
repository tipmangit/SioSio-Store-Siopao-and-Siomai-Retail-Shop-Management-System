<?php
session_start();
include("../config.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: ../loginreg/logreg.php");
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
  <title>Your Favorites - SioSio</title>

  <!-- Bootstrap 5.3.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Custom Bootstrap Styles (exact same ones used in products page) -->
  <link rel="stylesheet" href="../products/bootstrap-custom.css">
  <link rel="stylesheet" href="../products/custom.css">
  <!-- Original Custom CSS with lower priority than Bootstrap styles -->
  <link rel="stylesheet" href="favorites.css?v=<?php echo time(); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../headfoot/headfoot.css">
</head>

<body>
  <?php include("../headfoot/header.php")   ?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <h1 class="page-title">
        Your <span class="sio-highlight">Favorite</span> Products
      </h1>
      <p class="page-subtitle">All your saved <span class="sio-highlight">Sio</span>mai and <span class="sio-highlight">Sio</span>pao favorites in one place</p>
    </div>
  </section>

  <!-- Favorites Content -->
  <main class="container my-5">
    <?php if (count($favorites) > 0): ?>
      <div class="row g-4">
        <?php foreach ($favorites as $item): ?>
          <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm product-card">
              <div class="card-img-container">
                <img src="<?= htmlspecialchars($item['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="card-overlay">
                  <button class="btn btn-danger btn-sm remove-favorite-btn" data-product-id="<?= $item['id'] ?>">
                    <i class="bi bi-heart-fill"></i> Remove from Favorites
                  </button>
                </div>
              </div>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold"><?= htmlspecialchars($item['name']) ?></h5>
                <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($item['description']) ?></p>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <span class="h5 mb-0 text-danger fw-bold">₱<?= number_format($item['price'], 2) ?></span>
                  <button class="btn btn-primary add-to-cart-btn" data-product-id="<?= $item['id'] ?>">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-favorites text-center py-5">
        <div class="mb-4">
          <i class="bi bi-heart display-1 text-muted"></i>
        </div>
        <h3 class="mb-3">No Favorites Yet</h3>
        <p class="text-muted mb-4">Start browsing our delicious <span class="sio-highlight">Sio</span>mai and <span class="sio-highlight">Sio</span>pao to add your favorites!</p>
        <a href="../products/product.php" class="btn btn-primary btn-lg">
          <i class="bi bi-shop"></i> Browse Products
        </a>
      </div>
    <?php endif; ?>
  </main>

  <?php include("../headfoot/footer.php"); ?>

  <!-- Bootstrap 5.3.2 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JavaScript -->
  <script src="favorites.js"></script>
</body>
</html>
