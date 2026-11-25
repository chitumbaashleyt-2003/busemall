
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSE Marketplace</title>
    <link rel="stylesheet" href="../css/styles.css?v=11">
    <script src="../js/script.js" defer></script>
</head>
<body>

<header class="site-header">
    <div class="container nav-container">
        <a href="index.php" class="logo">
            BUSE <span>MARKETPLACE</span>
        </a>

        <button class="menu-toggle" id="menuToggle"
                aria-label="Toggle navigation"
                aria-expanded="false">☰</button>

        <nav id="mainNav">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="shops.php">Shops</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="Admin.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="admin-login.php">Admin Login</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="online-payment.php" class="nav-login">Online Payment</a>
                <a href="admin-login.php">Admin Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main>
