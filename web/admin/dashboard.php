<?php
require_once '../auth.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

// Fetch stats
$customer_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'customer'")->fetch_assoc()['c'];
$loyal_count = $conn->query("SELECT COUNT(*) as c FROM ml_predictions WHERE loyalty_level = 'High'")->fetch_assoc()['c'];
$avg_discount = $conn->query("SELECT AVG(recommended_discount) as avg_d FROM ml_predictions")->fetch_assoc()['avg_d'];

// Fetch all customers with their loyalty data
$query = "
    SELECT u.id, u.name, u.email, 
           m.loyalty_level, m.recommended_discount, m.special_card
    FROM users u
    LEFT JOIN ml_predictions m ON u.id = m.customer_id
    WHERE u.role = 'customer'
";
$result = $conn->query($query);
?>
<?php include '../includes/header.php'; ?>
<div class="container">

    <div class="row display-flex justify-content-between align-items-center mb-4">
        <div class="col-8">
            <h2>Admin Dashboard</h2>
        </div>
        <div class="col-4 text-end">
            <a href="import_predictions.php" class="btn btn-warning"><i class="bi bi-gear"></i> Update ML Data</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text fs-2">
                        <?php echo $customer_count; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">High Loyalty Customers</h5>
                    <p class="card-text fs-2">
                        <?php echo $loyal_count; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Avg Discount Rate</h5>
                    <p class="card-text fs-2">
                        <?php echo number_format($avg_discount ?? 0, 1); ?>%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="card p-3">
        <h4>Customer Loyalty Status</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Loyalty Level</th>
                        <th>Discount</th>
                        <th>Special Card</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php echo $row['id']; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['email']); ?>
                                </td>
                                <td>
                                    <!-- Badge logic -->
                                    <?php
                                    $badge = 'secondary';
                                    if ($row['loyalty_level'] == 'High')
                                        $badge = 'success';
                                    if ($row['loyalty_level'] == 'Medium')
                                        $badge = 'info';
                                    ?>
                                    <span class="badge bg-<?php echo $badge; ?>">
                                        <?php echo $row['loyalty_level'] ?? 'N/A'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $row['recommended_discount'] ? $row['recommended_discount'] . '%' : '0%'; ?>
                                </td>
                                <td>
                                    <?php if ($row['special_card'] && $row['special_card'] != 'None'): ?>
                                        <i class="bi bi-credit-card-2-front-fill text-warning"></i>
                                        <?php echo $row['special_card']; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No customers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    </div>
<?php include '../includes/footer.php'; ?>