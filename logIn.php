<?php
session_start();
include "connect.php";

$email = $Userpassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $Userpassword = $_POST['password'];

    if (!empty($email) && !empty($Userpassword)) {
        // Check in Owner table
        $query_owner = "SELECT * FROM Ownert WHERE Email='$email'";
        $result_owner = $conn->query($query_owner);
        if ($result_owner->num_rows == 1) {
            $row_owner = $result_owner->fetch_assoc();
            $hashed_password_owner = $row_owner['Password'];
            // Verify password
            if (password_verify($Userpassword, $hashed_password_owner)) {
                // Redirect owner to owner's home page
                
                $_SESSION['owner_email'] = $email;
                header("Location: ownerPage.php");
                exit;
            }
        }

        // Check in Police table
$query_police = "SELECT * FROM Police WHERE Email='$email'";
$result_police = $conn->query($query_police);

if ($result_police->num_rows == 1) {
    $row_police = $result_police->fetch_assoc();
    $hashed_password_police = $row_police['Password'];

    // Verify password
    if (password_verify($Userpassword, $hashed_password_police)) {
      
        
            // Redirect to police page
            $_SESSION['police_email'] = $email;
            $_SESSION['policeId'] = $row_police['EmployeeID'];
            $_SESSION['policeType'] = $row_police['Position'];
            $_SESSION['policeName'] = $row_police['Name'];

            header("Location: policePage.php");
            exit;
        
    }
}


        // Check in Admin table
        $query_admin = "SELECT * FROM Admint WHERE Email='$email'";
        $result_admin = $conn->query($query_admin);
        if ($result_admin->num_rows == 1) {
            $row_admin = $result_admin->fetch_assoc();
            $hashed_password_admin = $row_admin['Password'];
            // Verify password
            if (password_verify($Userpassword, $hashed_password_admin)) {
                // Redirect admin to admin's home page
                
                $_SESSION['admin_email'] = $email;
                header("Location: admin.php");
                exit;
            }
        }

        // If no matching user is found, set error message in session and redirect back
        $errorMsg = '<div class="error-message"> البريد الالكتروني غير صحيح \ كلمة المرور غير صحيحة</div>';
        
    }
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* ملف الستايل */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(to right, #9c27b0, #8ecdff);
}
body, .form-container{
    direction: rtl;
}

.form-container {
    width: 400px;
    background-color: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
    text-align: center;
    margin-bottom: 20px;
}

.input-box {
    margin-bottom: 20px;
}

.input-box input {
    width: 100%;
    height: 40px;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.input-box input:focus {
    border-color: blueviolet;
    outline: none;
}
.button {
    width: 100%;
    height: 40px;
    background-color: blueviolet;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #8a2be2;
}

.signin {
    text-align: center;
    margin-top: 20px;
}

.signin a {
    color: blueviolet;
    text-decoration: none;
}

.signin a:hover {
    text-decoration: underline;
}
.logo{
    float: right;
    position: absolute;
    top: -17px;
    right: 35px;
    width: 90px;
    height: auto;
      margin-top: 1px;
}
.home-link{
    position: fixed;
    top: 20px;
    left: 20px;
    text-decoration: none;
    color: #708090;
    font-size: 20px;
    z-index: 999;
}
.forgot-password{
    text-decoration: none;
    display: block;
    margin-top:  30px;
    margin-bottom:  10px;
}
.white-bar {
    background-color: white;
    height: 60px; 
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 999; 
    display: flex;
    align-items: center;
    padding: 0 20px;
}

.nav-item.active a {
     border-bottom: 2px solid cornflowerblue; /* يمكنك تعديل اللون والسمك حسب التفضيلات */
}
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
     font-family:inherit, sans-serif ;
        }
.navbar-nav {
    justify-content: space-between; /* توزيع العناصر بالتساوي */
    align-items: center; /* للوسطية الرأسية */
    height: 100%; /* لاستخدام الارتفاع بشكل كامل */
}

.navbar-collapse {
    margin-top: -17px; /* يمكنك تعديل هذه القيمة حسب ارتفاع شريط النافيجيشن */
}
.nav-item {
    margin-right: 100px; /* ضبط هذه القيمة حسب الحاجة لتحريك الروابط إلى اليسار */
    margin-left: -90px;
}
.error-message {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <img src="logo-removebg-preview.png" alt="لوقو" width="90" height="90" class="d-inline-block align-top logo" style="margin-right: 5px; " >
                
                <li class="nav-item " style="margin-top: 20px;">
                    <a class="nav-link" href="Home.html"> الرئيسية  </a>
                </li>
                <li class="nav-item" style="margin-top: 20px;">
                    <a class="nav-link" href="SignUpOwner.php"> تسجيل جديد كمالك درج</a>
                </li>
                <li class="nav-item"  style="margin-top: 20px;">
                    <a class="nav-link" href="SignUpPolice.php">تسجيل جديد كشرطي </a>
                </li>
                <li class="nav-item  active"  style="margin-top: 20px;">
                    <a class="nav-link" href="logIn.php">تسجيل دخول  </a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="form-container">
        <!-- Your form HTML remains unchanged -->
        <h2>تسجيل الدخول</h2>
        <?php if (!empty($errorMsg)) : ?>
        <p><?php echo $errorMsg; ?></p>
    <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box">
                <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="كلمة المرور" required>
            </div>
            
            <a href="Reset.html" class="forgot-password">  نسيت كلمة المرور؟  </a>
            <button type="submit" class="button" name="submit">تسجيل الدخول</button>
        </form>
    </div>

</body>
</html>
