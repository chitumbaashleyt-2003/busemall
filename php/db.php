
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "127.0.0.1";
$port = 3306;
$dbname = "buse_marketplace";
$username = "root";
$password = ""; // Default XAMPP development setup; set this if you configure a password.

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    exit("Database connection failed. Check that XAMPP MySQL is running and the database settings are correct.");
}
?>
