<?php
include __DIR__ . "/header.php";

$error = "";
$confirmation = "";
$paymentOptions = ["ZiG", "USD", "Card"];
$item = "";
$amount = "";
$paymentOption = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item = trim($_POST["item"] ?? "");
    $amount = trim($_POST["amount"] ?? "");
    $paymentOption = $_POST["payment_option"] ?? "";
    $numericAmount = filter_var($amount, FILTER_VALIDATE_FLOAT);

    if ($item === "" || strlen($item) > 150) {
        $error = "Enter an item or order description (up to 150 characters).";
    } elseif (
        $numericAmount === false ||
        $numericAmount <= 0 ||
        $numericAmount > 99999999.99
    ) {
        $error = "Enter a valid payment amount.";
    } elseif (!in_array($paymentOption, $paymentOptions, true)) {
        $error = "Choose ZiG, USD, or Card.";
    } else {
        $confirmation = "Demo only: no payment was processed or charged.";
    }
}
?>

<section class="page-banner">
    <div class="container">
        <h1>Online Payment</h1>
        <p>Choose a payment option for your marketplace order.</p>
    </div>
</section>

<section class="form-section">
    <div class="container">
        <form class="form-card" method="POST" action="online-payment.php">
            <h2>Payment Details</h2>
            <p>This demonstration does not process real payments.</p>

            <?php if ($error): ?>
                <div class="alert" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <?php if ($confirmation): ?>
                <div class="alert success" role="status">
                    <?= htmlspecialchars($confirmation, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <label for="item">Item or Order Description</label>
            <input id="item" name="item" type="text" maxlength="150"
                   value="<?= htmlspecialchars($item, ENT_QUOTES, "UTF-8") ?>"
                   required>

            <label for="amount">Amount</label>
            <input id="amount" name="amount" type="number"
                   min="0.01" max="99999999.99" step="0.01"
                   value="<?= htmlspecialchars($amount, ENT_QUOTES, "UTF-8") ?>"
                   required>

            <label for="payment_option">Payment Option</label>
            <select id="payment_option" name="payment_option" required>
                <option value="">Choose ZiG, USD, or Card</option>
                <?php foreach ($paymentOptions as $option): ?>
                    <option value="<?= htmlspecialchars($option, ENT_QUOTES, "UTF-8") ?>"
                        <?= $paymentOption === $option ? "selected" : "" ?>>
                        <?= htmlspecialchars($option, ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <p class="form-note">
                Card payments are not connected to a payment provider.
                Do not enter card numbers or security codes here.
            </p>

            <button type="submit" class="btn">Continue</button>

        </form>
    </div>
</section>

<?php include __DIR__ . "/footer.php"; ?>
