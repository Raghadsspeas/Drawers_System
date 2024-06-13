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

$password = $confirmPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_GET['token'];
    $email = $_GET['email'];
    // $token = isset($_SESSION['reset_token']) ? $_SESSION['reset_token'] : '';

    // Check if the token is set and not empty
    if (!isset($token) || empty($token)) {
        // Token is not set or empty, handle the error (e.g., display an error message)
        echo "Invalid token.";
        exit;
    }

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

  

   
    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

   

    // Update password in the respective table
    $table = ''; // Variable to store the table name

    if ($result = $conn->query("SELECT 1 FROM Ownert WHERE Email='$email'")) {
        $table = 'Ownert';
    } elseif ($result = $conn->query("SELECT 1 FROM Police WHERE Email='$email'")) {
        $table = 'Police';
    } 

    if ($table != '') {
        $stmt = $conn->prepare("UPDATE $table SET Password=?, reset_token=NULL WHERE reset_token=?");
        $stmt->bind_param("ss", $passwordHash, $token);
        

        if ($stmt->execute()) {
            header("Location: logIn.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }


}

// Close connection
$conn->close();

?>
