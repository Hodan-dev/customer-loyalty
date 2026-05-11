<?php
require_once 'auth.php';

// Handle Login
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $loginResult = loginUser($email, $password);

    if ($loginResult === true) {
        if ($_SESSION['user_role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: customer/dashboard.php");
        }
        exit;
    } else {
        $error = $loginResult;
    }
}
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<div class="p-5 mb-4 bg-light rounded-3 text-center">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold text-primary">Smart Loyalty, Real Rewards</h1>
        <p class="col-md-8 fs-4 mx-auto">Get recognized for your loyalty. Our AI-driven system ensures you get the
            discounts and VIP treatment you deserve, automatically.</p>
        <a href="#login-section" class="btn btn-primary btn-lg px-4 gap-3">Get Started</a>
        <a href="#how-it-works" class="btn btn-outline-secondary btn-lg px-4">Learn More</a>
    </div>
</div>

<!-- How It Works Section -->
<div id="how-it-works" class="container px-4 py-5">
    <h2 class="pb-2 border-bottom text-center">How It Works</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-3 rounded">
                <i class="bi bi-cart-check-fill fs-1 text-primary"></i>
            </div>
            <div>
                <h2>1. Shop & Enjoy</h2>
                <p>Just shop as usual. Our system tracks your engagement and spending habits securely.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-3 rounded">
                <i class="bi bi-cpu-fill fs-1 text-primary"></i>
            </div>
            <div>
                <h2>2. AI Analysis</h2>
                <p>Our intelligent algorithms analyze your history to determine your Loyalty Level (Low, Medium, or
                    High).</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square bg-light text-dark flex-shrink-0 me-3 p-3 rounded">
                <i class="bi bi-gift-fill fs-1 text-primary"></i>
            </div>
            <div>
                <h2>3. Get Rewards</h2>
                <p>Unlock personalized discounts, coupons, and Special Cards (Silver, Gold, VIP) automatically!</p>
            </div>
        </div>
    </div>
</div>

<!-- About Section -->
<div class="bg-primary text-white p-5 rounded mt-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3>About This System</h3>
            <p class="lead">We believe loyalty should be rewarded fairly. Instead of random discounts, we use data
                science to identify our most valuable customers and treat them like royalty.</p>
        </div>
        <div class="col-md-4 text-center">
            <i class="bi bi-shield-lock-fill" style="font-size: 5rem;"></i>
        </div>
    </div>
</div>

<!-- Login / Register Section -->
<div class="container">
    <div id="login-section" class="row justify-content-center mt-5 mb-5">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4"><i class="bi bi-person-circle"></i> Login to Your Account</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Login</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <p class="mb-2">New here?</p>
                        <a href="register.php" class="btn btn-success">Create New Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>