<?php
// Connect to the database
require_once __DIR__ . "/db.php";

// Escape output to protect against HTML injection
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

// Fetch the latest 8 products
$products = [];

try {
    $stmt = $pdo->query(
        "SELECT * FROM products ORDER BY created_at DESC LIMIT 8"
    );
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    $products = [];
}

$isHomePage = true;

// Load the shared header
include __DIR__ . "/header.php";
?>

    <!-- HERO SECTION -->
    <section class="home-hero">
        <div class="home-hero-content">
            <p class="home-hero-eyebrow">BUSE CAMPUS MARKETPLACE</p>
            <h1>
                Shop with us at Bindura University of Science and Education
                <span class="home-hero-accent">Your Marketplace.</span>
            </h1>
            <p>
                Discover products from campus shops and local businesses.
                Browse products, compare prices and connect with sellers
                through BUSE Marketplace.
            </p>

            <a href="products.php" class="home-button home-button-primary">
                Explore Products →
            </a>
            <a href="shops.php" class="home-button home-button-outline">
                Browse Shops
            </a>
        </div>

        <div class="home-hero-logo-card">
            <img class="home-hero-logo" src="../assets/buse-logo.jpg"
                 alt="Bindura University of Science Education logo">
        </div>
    </section>

    <!-- SHOP CATEGORIES -->
    <section class="home-section">
        <div class="home-section-heading">
            <h2>Explore Our Shops</h2>
            <p>Find what you need from our different shop categories.</p>
        </div>
        <div class="shop-grid">
            <article class="home-shop-card">
                <div class="home-shop-icon">💻</div>
                <h3>Palpo Store</h3>
                <p>Laptops, mobile phones and technology accessories.</p>
                <a href="shop.php?shop=Palpo%20Store">View Shop</a>
                <a href="products.php?shop=Palpo%20Store">View Products →</a>
            </article>
            <article class="home-shop-card">
                <div class="home-shop-icon">🥬</div>
                <h3>Grocery Shop</h3>
                <p>Groceries and everyday household essentials.</p>
                <a href="shop.php?shop=Grocery%20Shop">View Shop</a>
                <a href="products.php?shop=Grocery%20Shop">View Products →</a>
            </article>
            <article class="home-shop-card">
                <div class="home-shop-icon">👕</div>
                <h3>Clothes Shop</h3>
                <p>Clothing, fashion and other wearable products.</p>
                <a href="shop.php?shop=Clothes%20Shop">View Shop</a>
                <a href="products.php?shop=Clothes%20Shop">View Products →</a>
            </article>
            <article class="home-shop-card">
                <div class="home-shop-icon">🧺</div>
                <h3>Masawo Shop</h3>
                <p>Locally manufactured and finished products.</p>
                <a href="shop.php?shop=Masawo%20Shop">View Shop</a>
                <a href="products.php?shop=Masawo%20Shop">View Products →</a>
            </article>
        </div>
    </section>

    <!-- LATEST PRODUCTS -->
    <section class="home-section home-products-section">
        <div class="home-section-heading">
            <h2>Latest Products</h2>
            <p>Browse the latest products available on BUSE Marketplace.</p>
        </div>
        <div class="home-product-grid">

            <?php if (!empty($products)): ?>

                <?php foreach ($products as $product): ?>

                    <?php
                    // Product image path relative to the php folder
                    $imageName = basename($product['image'] ?? '');
                    $imagePath = "uploads/" . $imageName;
                    $imageExists = $imageName !== ''
                        && is_file(__DIR__ . "/" . $imagePath);
                    ?>

                    <article class="home-product-card">

                        <?php if ($imageExists): ?>
                            <img
                                src="<?= e($imagePath) ?>"
                                alt="<?= e($product['name'] ?? 'Product') ?>"
                                class="home-product-image"
                            >
                        <?php else: ?>
                            <div class="home-product-image home-product-image-placeholder">
                                No image available
                            </div>
                        <?php endif; ?>

                        <div class="home-product-info">

                            <p class="home-product-shop">
                                <?= e($product['shop_name'] ?? 'BUSE Marketplace') ?>
                            </p>

                            <h3><?= e($product['name'] ?? 'Unnamed Product') ?></h3>

                            <p class="home-product-description">
                                <?= e($product['description'] ?? 'No description available.') ?>
                            </p>

                            <p class="home-product-price">
                                $<?= number_format((float)($product['price'] ?? 0), 2) ?>
                            </p>

                            <a href="products.php?shop=<?= rawurlencode(
                                $product['shop_name']
                            ) ?>" class="home-product-link">View Products</a>

                        </div>
                    </article>

                <?php endforeach; ?>

            <?php else: ?>

                <p class="home-empty-state">
                    No products are available yet. Please check back later.
                </p>

            <?php endif; ?>

        </div>
        <div class="home-section-action">
            <a href="products.php" class="home-button home-button-primary">
                View All Products →
            </a>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="home-section home-how-section">
        <div class="home-section-heading">
            <h2>How It Works</h2>
            <p>Discover products and connect with sellers in a few steps.</p>
        </div>
        <div class="home-steps">
            <article class="home-step">
                <div class="home-step-number">1</div>
                <h3>Browse</h3>
                <p>Explore shops and available product listings.</p>
            </article>
            <article class="home-step">
                <div class="home-step-number">2</div>
                <h3>Compare</h3>
                <p>Check product descriptions and listed prices.</p>
            </article>
            <article class="home-step">
                <div class="home-step-number">3</div>
                <h3>Connect</h3>
                <p>Contact the seller to enquire about a product.</p>
            </article>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="home-call-to-action">
        <h2>Ready to Explore?</h2>
        <p>Visit the products page and see what is available.</p>
        <a href="products.php" class="home-button home-button-primary">
            Browse Products →
        </a>
    </section>

<?php
// Load the shared footer
include __DIR__ . "/footer.php";
?>