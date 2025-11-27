<?php
require_once '../db.php';
require_once '../cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id'];
    cart_add($productId, 1);
    header("Location: verkkokauppa.php");
    exit;
}

$categories = [];
$res = $mysqli->query("SELECT id, name FROM categories ORDER BY id");
while ($row = $res->fetch_assoc()) {
    $categories[] = $row;
}
$res->free();

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

if ($selectedCategory > 0) {
    $stmt = $mysqli->prepare("
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE p.category_id = ?
        ORDER BY p.id
    ");
    $stmt->bind_param("i", $selectedCategory);
} else {
    $stmt = $mysqli->prepare("
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id
        ORDER BY p.id
    ");
}

$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$cartCount = cart_get_count();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tuottajamarket – Verkkokauppa</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="verkkokauppa.css" />
  <style>
   body { background: url("raphael-rychetsky-li9JfUHQfOY-unsplash.jpeg"); }
</style>

</head>
<body>

<header class="site-header">
    <div class="header-left">
      <a href="../etusivu/etusivu.html" class="logo-link">
        <img src="OFFICIAL LOGO1.png" alt="Tuottajamarket logo" class="logo-image" />
      </a>
    </div>
    <nav class="header-right">
      <a href="../etusivu/etusivu.html" class="nav-link">Etusivu</a>
      <a href="../ostoskori/ostoskori.php" class="nav-link cart-link">
        <i class="fa-solid fa-cart-shopping"></i>
        Ostoskori (<?= $cartCount ?>)
      </a>
    </nav>
</header>

<main class="shop-page">

    <section class="shop-header">
      <h1>Verkkokauppa</h1>

      <div class="search-wrapper">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="search" placeholder="Etsi tuotteita..." class="search-input" />
      </div>

      <div class="filters-row">
        <div class="filters-left">

          <a href="verkkokauppa.php" class="filter-checkbox <?= $selectedCategory === 0 ? 'active' : '' ?>">
            Kaikki
          </a>

          <?php foreach ($categories as $cat): ?>
            <a href="verkkokauppa.php?category=<?= $cat['id'] ?>" 
               class="filter-checkbox <?= $selectedCategory === (int)$cat['id'] ? 'active' : '' ?>">
               <?= htmlspecialchars($cat['name']) ?>
            </a>
          <?php endforeach; ?>

        </div>

        <div class="filters-right">
          Yhteensä <?= count($products) ?> tuotetta
        </div>
      </div>
    </section>

    <section class="products-grid">

      <?php foreach ($products as $product): ?>
      <article class="product-card" data-category="<?= $product['category_id'] ?>">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p class="product-category"><?= htmlspecialchars($product['category_name']) ?></p>

        <p class="product-description">
          <?= nl2br(htmlspecialchars($product['description'])) ?>
        </p>

        <div class="product-footer">
          <span class="product-price">
            <?= number_format($product['prize'], 2, ',', '') ?>€
          </span>

          <form method="post">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button class="product-button" name="add_to_cart">Osta</button>
          </form>
        </div>
      </article>
      <?php endforeach; ?>

      <?php if (empty($products)): ?>
        <p>Ei tuotteita tässä kategoriassa.</p>
      <?php endif; ?>

    </section>

</main>

<footer class="site-footer">
  © 2025 Veeti Virtanen — Viitaniemi Gradia
</footer>

<script src="verkkokauppa.js"></script>


</body>
</html>
