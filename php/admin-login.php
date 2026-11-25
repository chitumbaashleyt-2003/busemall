<?php
require_once __DIR__ . "/db.php";

if (($_SESSION["role"] ?? "") === "admin") {
    header("Location: Admin.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare(
        "SELECT id, name, password, role
         FROM users WHERE email = ? LIMIT 1"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        if ($user["role"] !== "admin") {
            $error = "This account is not an administrator. Use customer login.";
        } else {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            header("Location: Admin.php");
            exit;
        }
    } else {
        $error = "Incorrect administrator email or password.";
    }
}

include __DIR__ . "/header.php";
?>

<section class="form-section">
    <div class="container">
        <form class="form-card" method="POST" action="admin-login.php">
            <h1>Administrator Login</h1>
            <p>Sign in with an administrator account to manage products.</p>

            <?php if ($error): ?>
                <div class="alert" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <label for="email">Administrator Email</label>
            <input id="email" name="email" type="email"
                   required autocomplete="username">

            <label for="password">Password</label>
            <input id="password" name="password" type="password"
                   required autocomplete="current-password">

            <button type="submit" class="btn">Admin Login</button>

        </form>
    </div>
</section>

<?php include __DIR__ . "/footer.php"; ?>
