<?php
require_once '../db.php';
require_once '../cart.php';

$cartItems = cart_get_items();
$products = [];
$total = 0.0;

if (!empty($cartItems)) {
    $ids = array_keys($cartItems);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $mysqli->prepare("
        SELECT id, name, prize, category_id
        FROM products
        WHERE id IN ($placeholders)
    ");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $pid = $row['id'];
        $qty = $cartItems[$pid];
        $row['qty'] = $qty;
        $row['line_total'] = $qty * (float)$row['prize'];
        $products[] = $row;
        $total += $row['line_total'];
    }
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ostoskori – Tuottajamarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="ostoskori.css">
    <style>
   body { background: url("dan-meyers-IQVFVH0ajag-unsplash.jpeg"); }
</style>
</head>
<body>

<header class="site-header">
    <div class="header-left">
        <a href="../etusivu/etusivu.html" class="logo-link">
            <img src="OFFICIAL LOGO1.png" class="logo-image" />
        </a>
    </div>

    <nav class="header-right">
        <a href="../verkkokauppa/verkkokauppa.php" class="nav-link">
            <i class="fa-solid fa-store"></i> Kauppaan
        </a>
        <a href="../etusivu/etusivu.html" class="nav-link">Etusivu</a>
    </nav>
</header>

<main class="cart-page">
    <h1>Ostoskori</h1>

    <div class="cart-layout">

        <section class="cart-items">

            <?php if (empty($products)): ?>
                <p>Ostoskori on tyhjä.</p>

                <a href="../verkkokauppa/verkkokauppa.php" class="back-link">
                    <i class="fa-solid fa-angle-left"></i> Takaisin kauppaan
                </a>

            <?php else: ?>

                <?php foreach ($products as $item): ?>
                    <article class="cart-item">
                        <div class="cart-item-left">
                            <form method="post" action="">
                                <button type="submit" name="remove" value="<?= $item['id'] ?>" class="delete-btn">
                                    <i class="fa-solid fa-trash delete-icon"></i>
                                </button>
                            </form>

                            <div>
                                <h2><?= htmlspecialchars($item['name']) ?></h2>
                                <p class="product-category">Luokka <?= $item['category_id'] ?></p>
                            </div>
                        </div>

                        <div class="cart-item-right">
                            <input type="number" value="<?= $item['qty'] ?>" min="1" class="quantity-input" readonly>
                            <span class="item-price">
                                <?= number_format($item['line_total'], 2, ',', '') ?>€
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>

                <a href="../verkkokauppa/verkkokauppa.php" class="back-link">
                    <i class="fa-solid fa-angle-left"></i> Takaisin kauppaan
                </a>

                <div class="cart-total">
                    <span>Yhteensä</span>
                    <span class="total-price">
                        <?= number_format($total, 2, ',', '') ?>€
                    </span>
                </div>

            <?php endif; ?>

        </section>

        <section class="checkout-box">
         
            <input type placeholder="Nimi">
            <input type placeholder="Sähköpostiosoite">
            <input type placeholder="Puhelinnumero">


            <button class="order-button">
                Tee tilaus <i class="fa-solid fa-angle-right"></i>
            </button>
        </section>

    </div>
</main>

<footer class="site-footer">
    © 2025 Veeti Virtanen — Viitaniemi Gradia
</footer>

</body>
</html>
