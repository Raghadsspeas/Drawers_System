<?php
session_start(); // Start the session if not already started
// Initialize session variables for checkbox states if not already set
if (!isset($_SESSION['checkbox_states'])) {
    $_SESSION['checkbox_states'] = [];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update checkbox states in session variable
    if (isset($_POST['permissions'])) {
        $_SESSION['checkbox_states'] = $_POST['permissions'];
    }

    // Handle logout
if (isset($_POST['logout'])) {
    // Redirect to logout page
    header("Location: logIn.php");
    exit();
}
}
$adminEmail = $_SESSION['admin_email'];
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_information'])) {
        // Retrieve updated information from the form
        
        $email = $_POST['email'];
       

        // Update user information in the database
        $sql = "UPDATE admint SET  Email=? WHERE Email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name, $email, $phoneNumber, $adminEmail);

        if ($stmt->execute()) {
            $_SESSION['update_msg'] = "تم تحديث بياناتك بنجاح";
        } else {
            $_SESSION['error_msg'] = "حدث خطأ أثناء تحديث المعلومات";
        }

        $stmt->close();
    }
}
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
        } elseif (strlen($newPassword) < 8 || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/\d/", $newPassword) || !preg_match("/[!@#$%^&*()_+{}\[\]:;<>,.?/~]/", $newPassword))
        {
            $_SESSION['error_msg'] = "يجب أن تكون كلمة المرور على الأقل 8 أحرف وتحتوي على حرف كبير وحرف صغير ورقم واحد ورمزًا خاصًا على الأقل";
        } else {
            // Query to retrieve the stored password from the database
            $sql = "SELECT Password FROM admint WHERE Email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $adminEmail);
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
                $sql = "UPDATE admint SET Password=? WHERE Email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $hashedPassword, $adminEmail);

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
$updateMsg = isset($_SESSION['update_msg']) ? $_SESSION['update_msg'] : '';
$updatePassMsg = isset($_SESSION['update_pass']) ? $_SESSION['update_pass'] : '';
$errorMsg = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : '';

// Clear session variables after displaying messages
unset($_SESSION['update_msg']);
unset($_SESSION['update_pass']);
unset($_SESSION['error_msg']);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* CSS styles */
        body {
            background: #e7e7e7e7; 
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
            display: flex;
            min-height: 100vh; 
            flex-direction: column; 
            direction: rtl;
        }

        .admin-container {
           
             max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            text-align: center;
            margin-top: 50px;
        }

        .jumbotron {
            background: linear-gradient(to right, #9c27b0, #8ecdff);
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            position: relative;
            height: 200px;
            width: 700px;
        }
        .jumbotron h2 {
    text-align: center;
    font-weight: bold; /* تحديد عرض الخط */
    font-family: 'Arial' , Courier, monospace;
    position: relative;
            padding: 50px;
}
        .jumbotron img {
            max-width: 90px; 
            height: auto;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }

        .table-container {
            margin-top: 20px;
            text-align: right;
        }

        th, td {
            text-align: center;
        }

        th {
            background-color: #7da0ca;
            color: white;
        }

        .btn-primary {
            background-color: aliceblue;
            border-color: #a2bedc;
            color: white;
        margin-top: 30px;
        }
      .custom-button {
    background-color: #aaa; /* لون خلفية الزر */
    border: 2px solid #aaa; /* لون الحواف */
    color: white;
    padding: 10px 20px; /* تعديل الهوامش الداخلية */
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    transition-duration: 0.4s;
    cursor: pointer;
          border-radius: 3px;
}

    </style>
</head>
<body>

<div class="admin-container">
    <div class="jumbotron">
        <h2 style="font-size: 34px; ">التحكم في صلاحيات موظفي مركز الشرطة</h2>
    </div>

    <?php if (!empty($updateMsg)): ?>
            <p><?php echo $updateMsg; ?></p>
        <?php endif; ?>

        <?php if (!empty($updatePassMsg)): ?>
            <p><?php echo $updatePassMsg; ?></p>
        <?php endif; ?>

        <?php if (!empty($errorMsg)): ?>
            <p style="color: red;"><?php echo $errorMsg; ?></p>
        <?php endif; ?>

    <div class="table-container">
        <!-- Admin page HTML code -->
        <form method="post" action="update_permissions.php">
    <!-- Loop through police members -->
    <?php 
  $sql = "SELECT DISTINCT p.* 
  FROM police p
  INNER JOIN reports r ON p.EmployeeID = r.policeId 
  WHERE p.Position = 'police'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
while ($policeMember = $result->fetch_assoc()) {
  echo '<h3>' . $policeMember['Name'] . '</h3>';
  $policeId = $policeMember['EmployeeID'];

  // Loop through reports
  $sqlReports = "SELECT * FROM reports WHERE policeId = $policeId";
  $reportResult = $conn->query($sqlReports);

  if ($reportResult->num_rows > 0) {
      while ($report = $reportResult->fetch_assoc()) {
          echo '<label style="margin-right: 10px;">';
          echo '<input type="checkbox" name="permissions[' . $policeMember['EmployeeID'] . '][' . $report['ReportID'] . '][can_view]"';
          // Check if checkbox should be checked based on session data
          if (isset($_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_view'])) {
              echo ' checked';
          }
          echo '>';
          echo 'عرض بلاغ رقم:  ' . $report['ReportID'];
          echo '</label>';

          echo '<label style="margin-right: 60px;">';
          echo '<input type="checkbox" name="permissions[' . $policeMember['EmployeeID'] . '][' . $report['ReportID'] . '][can_edit]"';
          // Check if checkbox should be checked based on session data
          if (isset($_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_edit'])) {
              echo ' checked';
          }
          // Disable the checkbox for can_edit if can_view is 0
          if (!isset($_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_view']) || $_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_view'] == 0) {
              echo ' disabled';
          }
          echo '>';
          echo 'تعديل بلاغ رقم: ' . $report['ReportID'];
          echo '</label>';

          echo '<label style="margin-right: 60px;">';
          echo '<input type="checkbox" name="permissions[' . $policeMember['EmployeeID'] . '][' . $report['ReportID'] . '][can_print]"';
          // Check if checkbox should be checked based on session data
          if (isset($_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_print'])) {
              echo ' checked';
          }
          // Disable the checkbox for can_print if can_view is 0
          if (!isset($_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_view']) || $_SESSION['checkbox_states'][$policeMember['EmployeeID']][$report['ReportID']]['can_view'] == 0) {
              echo ' disabled';
          }
          echo '>';
          echo 'طباعة بلاغ رقم: ' . $report['ReportID'];
          echo '</label>';

          echo '<br>';
      }
  }
}
}


    ?>
    
    <button type="submit" class="custom-button" style="padding: 5px 20px; float: left; margin-bottom: -20px;">تحديث</button>
</form>


 <!-- Bootstrap JS scripts -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<!-- Update Information and Reset Password Buttons -->
<div style="display: flex; justify-content: center; margin-top: 40px;">
    <!-- زر العرض -->
    <button type="button" class="btn btn-primary" style="background-color: #480082; border-color: #480082; margin-right: 10px;" data-toggle="modal" data-target="#userInfoModal">
        عرض / تحديث معلوماتي
    </button>
    <!-- مسافة -->
    <div style="width: 7px;"></div>
    <!-- زر إعادة التعيين -->
    <button type="button" class="btn btn-primary" style="background-color: #480082; border-color: #480082; margin-left: 10px;" data-toggle="modal" data-target="#resetPasswordModal">
        إعادة تعيين كلمة المرور
    </button>
</div>

    <!-- Update Information Modal -->
    <div class="modal" id="userInfoModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" >  تحديث معلومات المستخدم  </h4>
                </div>
                <div class="modal-body" >
                    
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        
                        <div class="form-group" style="text-align: right;">
                            <label for="email"> البريد الإلكتروني: </label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : ''; ?>" >
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="update_information" style="background-color: dodgerblue;">  تحديث  </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
   
    <!-- Reset Password Modal -->
<div class="modal" id="resetPasswordModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إعادة تعيين كلمة المرور</h4>
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
                    <button type="submit" class="btn btn-primary" name="reset_password" style="background-color: dodgerblue;"> إعادة تعيين كلمة المرور</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Logout Button -->

<div style="display: flex; justify-content: center; margin-top: 20px;">
    <form id="logoutForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button type="submit" class="btn btn-danger" style="margin-top: 10px;">
            <i class="fas fa-sign-out-alt"></i> تسجيل خروج
        </button>
        <input type="hidden" name="logout">
    </form>
</div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
