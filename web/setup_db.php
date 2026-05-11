<?php
require_once 'config.php';

$sql = file_get_contents('../db/schema.sql');

if (!$sql) {
    die("Error: Could not read schema.sql");
}

// Execute multi_query
if ($conn->multi_query($sql)) {
    echo "<h1>Database Schema Applied Successfully!</h1>";
    echo "<p>Tables created: users, ml_predictions, coupons.</p>";
    echo "<a href='index.php'>Go to Home</a>";

    // Clear results to avoid sync errors
    while ($conn->next_result()) {
        ;
    }
} else {
    echo "Error applying schema: " . $conn->error;
}
?>