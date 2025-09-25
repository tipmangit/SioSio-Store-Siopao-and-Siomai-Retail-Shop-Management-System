<?php
include("../config.php");

echo "<h2>Debug: OTP Verifications Table</h2>";

// Check if table exists
$result = $con->query("SHOW TABLES LIKE 'otp_verifications'");
if ($result->num_rows > 0) {
    echo "<p>✓ Table 'otp_verifications' exists</p>";
    
    // Show all OTP records for password reset
    $stmt = $con->prepare("SELECT * FROM otp_verifications WHERE otp_type = 'password_reset' ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<h3>Recent Password Reset OTPs:</h3>";
    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Email</th><th>OTP Code</th><th>Created At</th><th>Expires At</th><th>Is Verified</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td><strong>" . $row['otp_code'] . "</strong></td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['expires_at'] . "</td>";
            echo "<td>" . ($row['is_verified'] ? 'YES' : 'NO') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ No OTP records found in database!</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Table 'otp_verifications' does not exist!</p>";
    echo "<p>You need to create the table first.</p>";
}

$con->close();
?>