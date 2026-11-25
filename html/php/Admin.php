
<?php
require_once "db.php";

if (
    !isset($_SESSION["user_id"]) ||
    ($_SESSION["role"] ?? "") !== "admin"
) {
    http_response_code(403);
    exit("Access denied. Administrator login required.");
}

$error = "";
$success = "";
$shops = [
    "Palpo Store",
    "Grocery Shop",
    "Clothes Shop",
    "Masawo Shop"
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    // ADD PRODUCT
    if ($action === "add") {
        $name = trim($_POST["name"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $price = $_POST["price"] ?? "";
        $shop = $_POST["shop_name"] ?? "";

        if (
            $name === "" ||
            !is_numeric($price) ||
            (float)$price < 0 ||
            !in_array($shop, $shops, true)
        ) {
            $error = "Enter a valid name, price and shop.";
        } elseif (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] !== UPLOAD_ERR_OK &&
            $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {
            $error = "Image upload failed. Please try again.";
        } else {
            $imagePath = null;
            $savedPath = null;

            try {
                if (
                    isset($_FILES["image"]) &&
                    $_FILES["image"]["error"] === UPLOAD_ERR_OK
                ) {
                    $file = $_FILES["image"];

                    if ($file["size"] > 3 * 1024 * 1024) {
                        throw new RuntimeException(
                            "Image must be 3 MB or smaller."
                        );
                    }

                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file["tmp_name"]);

                    $allowed = [
                        "image/jpeg" => "jpg",
                        "image/png" => "png",
                        "image/webp" => "webp"
                    ];

                    if (!isset($allowed[$mime])) {
                        throw new RuntimeException(
                            "Use a JPG, PNG or WebP image."
                        );
                    }

                    if (@getimagesize($file["tmp_name"]) === false) {
                        throw new RuntimeException(
                            "The uploaded file is not a valid image."
                        );
                    }

                    $folder = __DIR__ . "/uploads/";

                    if (
                        !is_dir($folder) &&
                        !mkdir($folder, 0755, true)
                    ) {
                        throw new RuntimeException(
                            "Cannot create the uploads folder."
                        );
                    }

                    $filename = bin2hex(random_bytes(16))
                        . "." . $allowed[$mime];

                    $destination = $folder . $filename;

                    if (!move_uploaded_file(
                        $file["tmp_name"], $destination
                    )) {
                        throw new RuntimeException(
                            "Could not save the image."
                        );
                    }

                    $savedPath = $destination;
                    $imagePath = "uploads/" . $filename;
                }

                $stmt = $pdo->prepare(
                    "INSERT INTO products
                    (name, description, price, shop_name, image, created_by)
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                $stmt->execute([
                    $name,
                    $description,
                    $price,
                    $shop,
                    $imagePath,
                    $_SESSION["user_id"]
                ]);

                header("Location: Admin.php?added=1");
                exit;

            } catch (Throwable $e) {
                if ($savedPath && is_file($savedPath)) {
                    unlink($savedPath);
                }

                $error = $e instanceof RuntimeException
                    ? $e->getMessage()
                    : "Could not save the product. Please try again.";
            }
        }
    }

    // DELETE PRODUCT
    if ($action === "delete") {
        $id = filter_var(
            $_POST["product_id"] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            $error = "Invalid product selected.";
        } else {
            $stmt = $pdo->prepare(
                "SELECT image FROM products WHERE id = ?"
            );
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            if ($product) {
                $stmt = $pdo->prepare(
                    "DELETE FROM products WHERE id = ?"
                );
                $stmt->execute([$id]);

                if (!empty($product["image"])) {
                    $path = __DIR__ . "/" . $product["image"];

                    if (
                        str_starts_with(
                            $product["image"], "uploads/"
                        ) &&
                        is_file($path)
                    ) {
                        unlink($path);
                    }
                }

                header("Location: Admin.php?deleted=1");
                exit;
            }

            $error = "Product not found.";
        }
    }
}

$stmt = $pdo->query(
    "SELECT * FROM products ORDER BY created_at DESC"
);
$products = $stmt->fetchAll();

include "header.php";
?>

<section class="page-banner">
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?>.
           Manage marketplace products here.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET["added"])): ?>
            <div class="alert success">Product uploaded successfully.</div>
        <?php endif; ?>

        <?php if (isset($_GET["deleted"])): ?>
            <div class="alert success">Product deleted successfully.</div>
        <?php endif; ?>

        <div class="admin-layout">
            <div class="dashboard-card">
                <h2>Add a Product</h2>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">

                    <label for="name">Product Name</label>
                    <input id="name" name="name" type="text"
                           maxlength="150" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                              rows="4"></textarea>

                    <label for="price">Price (USD)</label>
                    <input id="price" name="price" type="number"
                           min="0" max="99999999.99"
                           step="0.01" required>

                    <label for="shop_name">Select Shop</label>
                    <select id="shop_name" name="shop_name" required>
                        <option value="">Choose a shop</option>
                        <?php foreach ($shops as $shop): ?>
                            <option value="<?= htmlspecialchars($shop) ?>">
                                <?= htmlspecialchars($shop) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="image">Product Picture (JPG, PNG, WebP)</label>
                    <input id="image" name="image" type="file"
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                    <button type="submit" class="btn">
                        Upload Product
                    </button>
                </form>
            </div>

            <div class="dashboard-card">
                <h2>Manage Products (<?= count($products) ?>)</h2>

                <?php if (!$products): ?>
                    <p>No products have been uploaded yet.</p>
                <?php endif; ?>

                <?php foreach ($products as $product): ?>
                    <div class="admin-product">
                        <img
                            src="<?= !empty($product["image"])
                                ? htmlspecialchars($product["image"])
                                : 'https://placehold.co/100x100/eaf4ff/0756a5?text=Item' ?>"
                            alt="">

                        <div style="flex:1; min-width:0">
                            <strong>
                                <?= htmlspecialchars($product["name"]) ?>
                            </strong>
                            <p>
                                <?= htmlspecialchars($product["shop_name"]) ?>
                                · $<?= number_format(
                                    (float)$product["price"], 2
                                ) ?>
                            </p>
                        </div>

                        <form method="POST"
                              onsubmit="return confirm('Delete this product?')">
                            <input type="hidden" name="action"
                                   value="delete">
                            <input type="hidden" name="product_id"
                                   value="<?= (int)$product["id"] ?>">
                            <button type="submit" class="btn-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
