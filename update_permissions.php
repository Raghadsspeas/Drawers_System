<?php
session_start();

// Check if permissions array is set in the POST data
if (isset($_POST['permissions'])) {
    $_SESSION['checkbox_states'] = $_POST['permissions'];
    // Handle permission updates here (e.g., update database)
}

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drawersystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if permissions array is set in the POST data
    if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
        $_SESSION['checkbox_states'] = $_POST['permissions'];
        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE police_permissions SET can_view = ?, can_edit = ?, can_print = ? WHERE policeId  = ? AND ReportID = ?");

        // Bind parameters
        $stmt->bind_param("iiiii", $canView, $canEdit, $canPrint, $policeID, $reportID);

        // Loop through each police member's permissions
        foreach ($_POST['permissions'] as $policeID => $reports) {
            // Loop through each report's permissions
            foreach ($reports as $reportID => $permissions) {
                // Set permission flags based on checkbox values
                $canView = isset($permissions['can_view']) ? 1 : 0;
                $canEdit = isset($permissions['can_edit']) ? 1 : 0;
                $canPrint = isset($permissions['can_print']) ? 1 : 0;

                // Execute the prepared statement
                $stmt->execute();
            }
        }
        // Close the statement
        $stmt->close();

        // Provide feedback to the user
        $_SESSION['update_msg'] = "تم تحديث الصلاحيات!";
    } else {
        $_SESSION['error_msg'] = "لم تقم بتحديث الصلاحيات";
    }
} else {
    $_SESSION['error_msg'] = "خطأ في الطلب ";
}

// Close connection
$conn->close();

// Redirect back to the admin page after processing
header("Location: admin.php");
exit();

?>
