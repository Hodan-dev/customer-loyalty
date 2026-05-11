<?php
require_once '../auth.php';

if (!isLoggedIn() || $_SESSION['user_role'] != 'customer') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch my loyalty data
$stmt = $conn->prepare("SELECT loyalty_level, recommended_discount, special_card FROM ml_predictions WHERE customer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$loyalty = ['loyalty_level' => 'N/A', 'recommended_discount' => 0, 'special_card' => 'None'];
if ($result->num_rows > 0) {
    $loyalty = $result->fetch_assoc();
} else {
    // If no prediction yet, show default
    $loyalty['loyalty_level'] = 'Pending Analysis';
}

// Styling based on level
$card_class = "bg-secondary";
if ($loyalty['loyalty_level'] == 'High')
    $card_class = "bg-primary";
if ($loyalty['loyalty_level'] == 'Medium')
    $card_class = "bg-info";

?>
<?php include '../includes/header.php'; ?>
<div class="container">

    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Hello,
                <?php echo htmlspecialchars($user_name); ?>!
            </h2>
            <p class="text-muted">Welcome to your loyalty dashboard.</p>
        </div>
    </div>

    <div class="row">
        <!-- Loyalty Card -->
        <div class="col-md-6 mb-4">
            <div class="card text-white <?php echo $card_class; ?> h-100">
                <div class="card-header">Your Status</div>
                <div class="card-body text-center">
                    <h1 class="display-4">
                        <?php echo $loyalty['loyalty_level']; ?>
                    </h1>
                    <?php if ($loyalty['special_card'] != 'None'): ?>
                        <div class="mt-3">
                            <span class="badge bg-warning text-dark fs-5">
                                <i class="bi bi-star-fill"></i>
                                <?php echo $loyalty['special_card']; ?> Member
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Discount Card -->
        <div class="col-md-6 mb-4">
            <div class="card text-dark bg-light h-100 border-primary">
                <div class="card-header">Your Discount</div>
                <div class="card-body text-center">
                    <h1 class="display-3 text-success">
                        <?php echo $loyalty['recommended_discount']; ?>%
                    </h1>
                    <p>Applied automatically at checkout</p>
                    <a href="coupons.php" class="btn btn-outline-primary mt-2">View My Coupons</a>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include '../includes/footer.php'; ?>