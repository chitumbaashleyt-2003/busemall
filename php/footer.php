
</main>

<footer class="site-footer<?= !empty($isHomePage) ? ' home-site-footer' : '' ?>">
    <div class="container footer-grid">
        <?php if (!empty($isHomePage)): ?>
        <div>
            <h3>BUSE Marketplace</h3>
            <p>
                A marketplace website concept designed to connect shoppers
                with campus shops and local businesses.
            </p>
        </div>

        <div>
            <h3>Quick Links</h3>
            <a href="./index.php">Home</a>
            <a href="./products.php">Products</a>
            <a href="./shops.php">Shops</a>
            <a href="./online-payment.php">Online Payment</a>
            <a href="./admin-login.php">Admin Login</a>
        </div>

        <div>
            <h3>University Information</h3>
            <p>Bindura University of Science Education</p>
            <p>Bindura, Mashonaland Central, Zimbabwe</p>
            <a href="https://www.buse.ac.zw/"
               target="_blank" rel="noopener noreferrer">
                Official BUSE Website ↗
            </a>
        </div>
        <?php else: ?>
        <div>
            <h3>BUSE Marketplace</h3>
            <p>Your university marketplace, all in one place.</p>
        </div>

        <div>
            <h3>Contact BUSE</h3>
            <p>Email: <a href="mailto:YOUR_OFFICIAL_BUSE_EMAIL">
                YOUR_OFFICIAL_BUSE_EMAIL</a></p>
            <p>Facebook:
                <a href="https://www.facebook.com/"
                   target="_blank" rel="noopener noreferrer">
                    BUSE Facebook Page
                </a>
            </p>
        </div>

        <div>
            <h3>Our Location</h3>
            <p>Bindura University of Science and Education,
               Bindura, Mashonaland Central, Zimbabwe.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="copyright">
        &copy; <?php echo date("Y"); ?>
        BUSE Marketplace. All rights reserved.
    </div>
</footer>

</body>
</html>
