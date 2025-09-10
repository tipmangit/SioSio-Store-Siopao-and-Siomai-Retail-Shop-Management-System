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
    <link rel="stylesheet" href="favorites.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joti+One&display=swap" rel="stylesheet">
</head>

<body>
    <?php include("../headfoot/header.php")   ?>
<main class="favorites-page">
    <h2 class="favorites-title">Your Favorites</h2>

    <div class="favorites-grid">
        <!-- Example Favorite Item -->
        <div class="favorite-item">
            <div class="favorite-img">
                <img src="https://media.istockphoto.com/id/1176853895/photo/chinese-dim-sum-shrimp-dumplings.jpg?s=612x612&w=0&k=20&c=vrUGqI7qH5Cq9IkLDv0A0SwNoi9cbp4VXec-C6JDZjI=" alt="Hakaw">
            </div>
            <div class="favorite-details">
                <h3 class="favorite-name">Shrimp Hakaw</h3>
                <p class="favorite-desc">Steamed dumpling with fresh shrimp filling.</p>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
        </div>

        <div class="favorite-item">
            <div class="favorite-img">
                <img src="https://media.istockphoto.com/id/1313776276/photo/chinese-siu-mai-dim-sum.jpg?s=612x612&w=0&k=20&c=8yUqUKr9e1BHZqCE_gbnGe9tDbIMHB9fD4P4cEYNd_g=" alt="Siomai Special">
            </div>
            <div class="favorite-details">
                <h3 class="favorite-name">Special Siomai</h3>
                <p class="favorite-desc">Juicy pork siomai with garlic topping.</p>
                <button class="add-to-cart-btn">Add to Cart</button>
            </div>
        </div>
    </div>
</main>

    <?php include("../headfoot/footer.php")   ?>

    <script src="script.js"></script>
</body>
</html>