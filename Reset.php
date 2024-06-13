<?php
session_start();

// Database configuration
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

$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in any of the tables (Owner, Police)
    $sql = "SELECT * FROM Ownert WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $sql = "SELECT * FROM Police WHERE Email='$email'";
        $result = $conn->query($sql);
    }

    if ($result->num_rows > 0) {
        // Generate and store reset token
        $token = uniqid();
        // $_SESSION['reset_token'] = $token;

        // Update database with reset token
        if ($result = $conn->query("SELECT 1 FROM Ownert WHERE Email='$email'")) {
            $table = 'Ownert';
        } elseif ($result = $conn->query("SELECT 1 FROM Police WHERE Email='$email'")) {
            $table = 'Police';
        }

        if ($table != '') {
            $sql = "UPDATE $table SET reset_token='$token' WHERE Email='$email'";
            if ($conn->query($sql) === TRUE) {
                // Send reset password email (simulate)
                $to = $email;
                $subject = "Password Reset";
                $message = "To reset your password, click on the following link: https://localhost/Changepassword.php?token=$token&email=$email";
                $headers = "From: drawersystem@gmail.com";
               

                echo "تم ارسال رابط اعادة تعيين كلمة المرور الى بريدك الالكتروني ";
                // header("Location: Changepassword.html");
                exit; // Stop execution after sending email
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    } else {
        echo "Invalid email.";
    }
}

// Close connection
$conn->close();
?>
