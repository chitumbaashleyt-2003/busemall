<?php
require_once __DIR__ . "/db.php";

$shops = $pdo->query(
    "SELECT shop_name, description FROM shops ORDER BY shop_name"
)->fetchAll();

$shopIcons = [
    "Palpo Store" => "💻",
    "Grocery Shop" => "🛒",
    "Clothes Shop" => "👕",
    "Masawo Shop" => "🏭"
];

include __DIR__ . "/header.php";
?>

<section class="page-banner">
    <div class="container">
        <h1>Our Shops</h1>
        <p>Discover the businesses serving the BUSE community.</p>
    </div>
</section>

<section class="section">
    <div class="container shop-grid">
        <?php if (!$shops): ?>
            <p class="empty-state">No shops are available yet.</p>
        <?php endif; ?>

        <?php foreach ($shops as $shop): ?>
            <?php $name = $shop["shop_name"]; ?>
            <article class="shop-card">
                <div class="shop-icon" aria-hidden="true">
                    <?= $shopIcons[$name] ?? "🏬" ?>
                </div>
                <h2><?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?></h2>
                <p><?= htmlspecialchars(
                    $shop["description"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?></p>
                <div class="shop-card-actions">
                    <a class="text-link"
                       href="shop.php?shop=<?= rawurlencode($name) ?>">
                        View Shop
                    </a>
                    <a class="text-link"
                       href="products.php?shop=<?= rawurlencode($name) ?>">
                        View Products
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php include __DIR__ . "/footer.php"; ?>
