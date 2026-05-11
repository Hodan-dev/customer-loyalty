<?php
require_once '../auth.php';

if (!isLoggedIn() || $_SESSION['user_role'] != 'customer') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mock Coupons for Demo (In real app, fetched from DB 'coupons' table)
// We populate this array based on loyalty level as a simulation if DB is empty
$mock_coupons = [
    ['code' => 'WELCOME10', 'discount' => '10%', 'status' => 'Active', 'expiry' => date('Y-m-d', strtotime('+30 days'))],
];

// If High loyalty, add more
// Need to fetch loyalty level again or store in session? Better to fetch.
$stmt = $conn->prepare("SELECT loyalty_level FROM ml_predictions WHERE customer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if ($row['loyalty_level'] == 'High') {
        $mock_coupons[] = ['code' => 'VIP50', 'discount' => '50%', 'status' => 'Active', 'expiry' => date('Y-m-d', strtotime('+7 days'))];
    }
}
?>
<?php include '../includes/header.php'; ?>
<div class="container">

    <div class="row">
        <div class="col-12 mb-3">
            <a href="dashboard.php" class="btn btn-outline-secondary">&larr; Back to Dashboard</a>
        </div>
        <div class="col-12">
            <h3>My Coupons</h3>
            <div class="card mt-3">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mock_coupons as $c): ?>
                                <tr>
                                    <td><code class="fs-5"><?php echo $c['code']; ?></code></td>
                                    <td>
                                        <?php echo $c['discount']; ?>
                                    </td>
                                    <td><span class="badge bg-success">
                                            <?php echo $c['status']; ?>
                                        </span></td>
                                    <td>
                                        <?php echo $c['expiry']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
<?php include '../includes/footer.php'; ?>