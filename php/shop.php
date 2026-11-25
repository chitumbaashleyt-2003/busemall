<?php
require_once __DIR__ . "/db.php";

$shopName = trim($_GET["shop"] ?? "");
$stmt = $pdo->prepare(
    "SELECT shop_name, description, location, email, phone, image
     FROM shops WHERE shop_name = ? LIMIT 1"
);
$stmt->execute([$shopName]);
$shop = $stmt->fetch();

if (!$shop) {
    http_response_code(404);
    include __DIR__ . "/header.php";
    ?>
    <section class="page-banner">
        <div class="container">
            <h1>Shop not found</h1>
            <p>The shop you requested is not available.</p>
            <a class="btn" href="shops.php">Browse Shops</a>
        </div>
    </section>
    <?php
    include __DIR__ . "/footer.php";
    exit;
}

include __DIR__ . "/header.php";
?>

<section class="page-banner">
    <div class="container">
        <h1><?= htmlspecialchars($shop["shop_name"], ENT_QUOTES, "UTF-8") ?></h1>
        <p><?= htmlspecialchars(
            $shop["description"] ?? "Marketplace shop",
            ENT_QUOTES,
            "UTF-8"
        ) ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <article class="dashboard-card shop-detail-card">
            <?php if (!empty($shop["image"])): ?>
                <img class="shop-detail-image"
                     src="<?= htmlspecialchars($shop["image"], ENT_QUOTES, "UTF-8") ?>"
                     alt="<?= htmlspecialchars($shop["shop_name"], ENT_QUOTES, "UTF-8") ?>">
            <?php endif; ?>

            <h2>About this shop</h2>
            <p><?= htmlspecialchars(
                $shop["description"] ?? "No shop description is available yet.",
                ENT_QUOTES,
                "UTF-8"
            ) ?></p>

            <?php if (!empty($shop["location"])): ?>
                <p><strong>Location:</strong>
                    <?= htmlspecialchars($shop["location"], ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($shop["email"])): ?>
                <p><strong>Email:</strong>
                    <a href="mailto:<?= htmlspecialchars($shop["email"], ENT_QUOTES, "UTF-8") ?>">
                        <?= htmlspecialchars($shop["email"], ENT_QUOTES, "UTF-8") ?>
                    </a>
                </p>
            <?php endif; ?>

            <?php if (!empty($shop["phone"])): ?>
                <p><strong>Phone:</strong>
                    <?= htmlspecialchars($shop["phone"], ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php endif; ?>

            <div class="shop-detail-actions">
                <a class="btn" href="products.php?shop=<?= rawurlencode($shop["shop_name"]) ?>">
                    View this shop's products
                </a>
                <a class="text-link" href="shops.php">Back to all shops</a>
            </div>
        </article>
    </div>
</section>

<?php include __DIR__ . "/footer.php"; ?>
