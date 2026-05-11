<?php
require_once '../auth.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['import'])) {
    $file_path = '../../ml/predictions.csv';

    if (file_exists($file_path)) {
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            // Skip header row
            fgetcsv($handle);

            $count = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // CSV Columns: Customer_ID, Loyalty_Level, Recommended_Discount, Special_Card, [Cluster optional]
                // We map CSV Customer_ID to our DB User ID. 
                // In a real app, we'd need a robust mapping. 
                // For this academic demo, we assume the IDs match or we update based on available users.

                $customer_id = (int) $data[0];
                $loyalty_level = $data[1];
                $discount = (float) $data[2];
                $special_card = $data[3];

                // Check if prediction row exists
                $check = $conn->prepare("SELECT id FROM ml_predictions WHERE customer_id = ?");
                $check->bind_param("i", $customer_id);
                $check->execute();
                $res = $check->get_result();

                if ($res->num_rows > 0) {
                    // Update
                    $stmt = $conn->prepare("UPDATE ml_predictions SET loyalty_level=?, recommended_discount=?, special_card=? WHERE customer_id=?");
                    $stmt->bind_param("sdsi", $loyalty_level, $discount, $special_card, $customer_id);
                } else {
                    // Insert
                    // First ensure user exists (optional, depends on FK constraint)
                    $stmt = $conn->prepare("INSERT INTO ml_predictions (customer_id, loyalty_level, recommended_discount, special_card) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isds", $customer_id, $loyalty_level, $discount, $special_card);
                }

                // We use try-catch or silent fail for FK constraint violations if user doesn't exist
                try {
                    $stmt->execute();
                    $count++;
                } catch (Exception $e) {
                    // Ignore violations for synthetic IDs that don't match real users
                }
            }
            fclose($handle);
            $message = "Import successful! Processed $count records.";
        } else {
            $message = "Error opening CSV file.";
        }
    } else {
        $message = "Predictions file not found. Run the ML model first.";
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Admin Dashboard - ML Integration</h2>
        <div class="card p-4">
            <h4>Import ML Predictions</h4>
            <p>Run this tool to update customer loyalty status based on the latest ML analysis.</p>

            <?php if ($message): ?>
                <div class="alert alert-info">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" name="import" class="btn btn-primary btn-lg">
                    <i class="bi bi-cloud-upload"></i> Import Predictions from CSV
                </button>
                <a href="dashboard.php" class="btn btn-secondary btn-lg ms-2">Back to Dashboard</a>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>