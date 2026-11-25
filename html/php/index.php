
<?php
require_once "db.php";

$stmt = $pdo->query(
    "SELECT * FROM products ORDER BY created_at DESC LIMIT 8"
);
$products = $stmt->fetchAll();

include "header.php";
?>

<section class="hero">
    <div class="container hero-content">
        <h1>Discover More at BUSE Marketplace</h1>
        <p>
            Explore electronics, groceries, fashion and locally
            manufactured products from shops serving our university
            community.
        </p>
        <a href="products.php" class="btn-light">Explore Products →</a>
        <a href="shops.php" class="btn-light">Discover Our Shops</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title">
            <h2>Shop by Category</h2>
            <p>Find what you need in one convenient marketplace.</p>
        </div>

        <div class="shop-grid">
            <div class="shop-card">
                <div class="shop-icon">💻</div>
                <h3>Palpo Store</h3>
                <p>Laptops, phones and electronic accessories.</p>
                <a class="text-link" href="shop.php?shop=Palpo%20Store">View Shop</a>
                <a class="text-link" href="products.php?shop=Palpo%20Store">
                    Explore Store →
                </a>
            </div>

            <div class="shop-card">
                <div class="shop-icon">🛒</div>
                <h3>Grocery Shop</h3>
                <p>Everyday groceries, food and household essentials.</p>
                <a class="text-link" href="shop.php?shop=Grocery%20Shop">View Shop</a>
                <a class="text-link" href="products.php?shop=Grocery%20Shop">
                    Explore Groceries →
                </a>
            </div>

            <div class="shop-card">
                <div class="shop-icon">👕</div>
                <h3>Clothes Shop</h3>
                <p>Clothing, fashion and everyday wear.</p>
                <a class="text-link" href="shop.php?shop=Clothes%20Shop">View Shop</a>
                <a class="text-link" href="products.php?shop=Clothes%20Shop">
                    Explore Fashion →
                </a>
            </div>

            <div class="shop-card">
                <div class="shop-icon">🏭</div>
                <h3>Masawo Shop</h3>
                <p>Finished products manufactured locally.</p>
                <a class="text-link" href="shop.php?shop=Masawo%20Shop">View Shop</a>
                <a class="text-link" href="products.php?shop=Masawo%20Shop">
                    Explore Products →
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:#eaf4ff">
    <div class="container">
        <div class="section-title">
            <h2>Recently Added Products</h2>
            <p>Discover what's available from our university shops.</p>
        </div>

        <div class="product-grid">
            <?php if (!$products): ?>
                <p class="empty-state">
                    Products will appear here once the shops upload them.
                </p>
            <?php endif; ?>

            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <img
                        src="<?= !empty($product['image'])
                            ? htmlspecialchars($product['image'])
                            : 'https://placehold.co/400x300/eaf4ff/0756a5?text=BUSE+Product' ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                        loading="lazy">

                    <div class="product-info">
                        <span class="shop-label">
                            <?= htmlspecialchars($product['shop_name']) ?>
                        </span>
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars(
                            $product['description'] ?? ''
                        ) ?></p>
                        <div class="price">
                            $<?= number_format((float)$product['price'], 2) ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center; margin-top:30px">
            <a href="products.php" class="btn">View All Products</a>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
