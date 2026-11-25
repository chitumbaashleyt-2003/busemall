
<?php
require_once "db.php";

$shops = $pdo->query(
    "SELECT shop_name FROM shops ORDER BY shop_name"
)->fetchAll(PDO::FETCH_COLUMN);
$selectedShop = trim($_GET["shop"] ?? "");

if ($selectedShop !== "" && !in_array($selectedShop, $shops, true)) {
    http_response_code(404);
    exit("Shop not found.");
}

if ($selectedShop !== "") {
    $stmt = $pdo->prepare(
        "SELECT * FROM products
         WHERE shop_name = ?
         ORDER BY created_at DESC"
    );
    $stmt->execute([$selectedShop]);
} else {
    $stmt = $pdo->query(
        "SELECT * FROM products ORDER BY created_at DESC"
    );
}
$products = $stmt->fetchAll();

include "header.php";
?>

<section class="page-banner">
    <div class="container">
        <h1><?= $selectedShop
            ? htmlspecialchars($selectedShop, ENT_QUOTES, "UTF-8") . " Products"
            : "Explore Products" ?></h1>
        <p><?= $selectedShop
            ? "Browse products available from this shop."
            : "Browse products available from BUSE Marketplace shops." ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="toolbar">
            <input type="search" id="searchInput"
                   placeholder="Search products...">

            <select id="shopFilter">
                <option value="">All Shops</option>
                <?php foreach ($shops as $shop): ?>
                    <option value="<?= htmlspecialchars(
                        $shop, ENT_QUOTES, "UTF-8"
                    ) ?>" <?= $selectedShop === $shop ? "selected" : "" ?>>
                        <?= htmlspecialchars($shop, ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card"
                    data-name="<?= htmlspecialchars(
                        strtolower($product['name']), ENT_QUOTES, 'UTF-8'
                    ) ?>"
                    data-description="<?= htmlspecialchars(
                        strtolower($product['description'] ?? ''),
                        ENT_QUOTES, 'UTF-8'
                    ) ?>"
                    data-shop="<?= htmlspecialchars(
                        $product['shop_name'], ENT_QUOTES, 'UTF-8'
                    ) ?>">

                    <img
                        src="<?= !empty($product['image'])
                            ? htmlspecialchars($product['image'])
                            : 'https://placehold.co/400x300/eaf4ff/0756a5?text=Product' ?>"
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

            <p id="emptyMessage" class="empty-state"
               <?= count($products) ? 'hidden' : '' ?>>
                <?= $selectedShop
                    ? "This shop has no products yet."
                    : "No products found. Try another search or shop." ?>
            </p>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
