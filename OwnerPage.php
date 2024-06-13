<?php
session_start();

if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    header("Location: logIn.php"); // Redirect to login page after logout
    exit();
}

// Fetch owner's name from the database
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

$ownerEmail = $_SESSION['owner_email'];
$sql = "SELECT Name, PhoneNumber FROM Ownert WHERE Email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ownerEmail);
$stmt->execute();
$stmt->bind_result($ownerName, $ownerPhoneNumber);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_information'])) {
        // Retrieve updated information from the form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];

        // Update user information in the database
        $sql = "UPDATE Ownert SET Name=?, Email=?, PhoneNumber=? WHERE Email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phoneNumber, $ownerEmail);

        if ($stmt->execute()) {
            $_SESSION['update_msg'] = "تم تحديث بياناتك بنجاح";
        } else {
            $_SESSION['error_msg'] = "حدث خطأ أثناء تحديث المعلومات";
        }

        $stmt->close();
    }
}

// Fetch data from the Reports table for the logged-in owner
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

$sql = "SELECT ReportID, MACAddress, Time, acceptance_status 
        FROM Reports 
        WHERE MACAddress = (SELECT MACAddress FROM Ownert WHERE Email = ?)";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['owner_email']);
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store the events
$events = array();

// Fetch each row and add it to the events array
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    // Add your database connection code here
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

    if (isset($_POST['reset_password'])) {
        // Retrieve current password, new password, and confirm new password from the form
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmNewPassword = $_POST['confirmNewPassword'];

        // Validate if the new password matches the confirm new password
        if ($newPassword !== $confirmNewPassword) {
            $_SESSION['error_msg'] = "كلمات المرور غير متطابقة";
        } elseif (strlen($newPassword) < 8 || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/\d/", $newPassword) || !preg_match("/[!@#$%^&*()_+{}\[\]:;<>,.?\/~]/", $newPassword))
        {
            $_SESSION['error_msg'] = "يجب أن تكون كلمة المرور على الأقل 8 أحرف وتحتوي على حرف كبير وحرف صغير ورقم واحد ورمزًا خاصًا على الأقل";
        } else {
            // Query to retrieve the stored password from the database
            $sql = "SELECT Password FROM Ownert WHERE Email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $ownerEmail);
            $stmt->execute();
            $stmt->bind_result($storedPassword);
            $stmt->fetch();
            $stmt->close();

            // Verify if the current password matches the stored password
            if (!password_verify($currentPassword, $storedPassword)) {
                $_SESSION['error_msg'] = "كلمة المرور الحالية غير صحيحة";
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $sql = "UPDATE Ownert SET Password=? WHERE Email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $hashedPassword, $ownerEmail);

                if ($stmt->execute()) {
                    $_SESSION['update_pass'] = "تم تغيير كلمة المرور بنجاح";
                } else {
                    $_SESSION['error_msg'] = "حدث خطأ أثناء إعادة تعيين كلمة المرور";
                }

                $stmt->close();
            }
        }
    }
}


?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body{
            direction: rtl;
            text-align: center;
        }
        .jumbotron {
            background: linear-gradient(to right, #9c27b0, #8ecdff);
            text-align: center;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .jumbotron h1 {
            font-weight: bold;
            margin-bottom: 30px;
        }
        .col-md-12 h2 {
            float: right; 
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }
        .btn-container {
            margin-top: 20px; 
            display: flex;
            flex-direction: row; 
            justify-content: center; 
        }
        .btn {
            margin: 0 1px;
        }
         .btn-primary:hover {
            background-color: #6c757d; /* لون جديد عند تمرير المؤشر */
            border-color: #6c757d; /* لون جديد للحدود عند تمرير المؤشر */
        }
        .modal-header .close {
         position: absolute;
         right: auto;
         left: 10px;
        }
        .assign-button {
            display: inline-block;
            margin-left: 10px;
            padding: 5px 10px;
            background-color:#9932CC;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .assign-button:hover {
            background-color: #6699ff;
        }
        
    </style>
</head>
<body>

<div class="container">
    <div class="jumbotron">
    <h1 class="display-4"> مرحبا، <?php echo $ownerName ; ?>! </h1>
        <p class="lead">  ادارة النظام الخاص بك وعرض البلاغات</p>
    </div>

    

    <!-- Display Report Details -->
    <?php if (isset($_SESSION['update_msg'])): ?>
        <p><?php echo $_SESSION['update_msg']; ?></p>
        <?php unset($_SESSION['update_msg']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['update_pass'])): ?>
        <p><?php echo $_SESSION['update_pass']; ?></p>
        <?php unset($_SESSION['update_pass']); ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <h2>  تفاصيل البلاغات  </h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th> رقم البلاغ</th>
                        <th> الرقم التسلسلي للجهاز </th>
                        <th>التاريخ والوقت</th>
                        <th> حالة البلاغ </th>
                        <th>  طباعة </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $event['ReportID']; ?></td>
                            <td><?php echo $event['MACAddress']; ?></td>
                            <td><?php echo $event['Time']; ?></td>
                            <td><?php echo $event['acceptance_status']; ?></td>
                            <td>    
                            <?php echo "<a href='printReport.php?report_id={$event['ReportID']}' class='assign-button' target='_print'> طباعة التقرير </a>"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS scripts -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


 <!-- Update Information Button -->
 <button type="button" class="btn btn-primary" style="background-color: #480082; border-color: #480082; "data-toggle="modal" data-target="#userInfoModal">
        عرض / تحديث معلوماتي
    </button>

    <!-- Update Information Modal -->
    <div class="modal" id="userInfoModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >  تحديث معلومات المستخدم  </h4>
                    <button type="button" class="close">&times;</button>
                </div>
                <div class="modal-body" >
                    
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group" style="text-align: right;">
                            <label for="name"> الاسم: </label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $ownerName; ?>" >
                        </div>
                        <div class="form-group" style="text-align: right;">
                            <label for="email"> البريد الإلكتروني: </label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['owner_email']) ? $_SESSION['owner_email'] : ''; ?>" >
                        </div>
                        <div class="form-group" style="text-align: right;">
                            <label for="phoneNumber"> رقم الجوال: </label>
                            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $ownerPhoneNumber; ?>" >
                        </div>
                        <button type="submit" class="btn btn-primary" name="update_information">  تحديث  </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Button -->
 
<button type="button" class="btn btn-primary" style="background-color: #480082; border-color: #480082; " data-toggle="modal" data-target="#resetPasswordModal">
   إعادة تعيين كلمة المرور
</button>

<!-- Reset Password Modal -->
<div class="modal" id="resetPasswordModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إعادة تعيين كلمة المرور</h4>
                <button type="button" class="close">&times;</button>
            </div>
            <div class="modal-body">
               
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <?php if (isset($_SESSION['error_msg'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error_msg']; ?></p>
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif?>

                    <div class="form-group" style="text-align: right;">
                        <label for="currentPassword"> كلمة المرور الحالية:  </label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="form-group" style="text-align: right;">
                        <label for="newPassword"> كلمة مرور جديدة: </label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="form-group"style="text-align: right;" >
                        <label for="confirmNewPassword"> تأكيد كلمة المرور الجديدة: </label>
                        <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="reset_password"> إعادة تعيين كلمة المرور</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Logout Button -->
<form id="logoutForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <button type="submit" class="btn btn-danger"style="margin-top: 20px;" >
        <i class="fas fa-sign-out-alt"></i> تسجيل خروج
    </button>
    <input type="hidden" name="logout">
</form>

</body>
</html>