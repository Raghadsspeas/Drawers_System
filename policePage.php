<?php
    session_start();
    include "connect.php";



    $pID = @(int)$_SESSION['policeId'] ;
    $pType = @trim($_SESSION['policeType']) ;
    $pName = @trim($_SESSION['policeName']) ;
    $pEmail = @trim($_SESSION['police_email']);
    if ($pID==0) {
        header('location: logIn.php');
        exit();
    }elseif($pID > 0 and $pType=='officer'){
        header('location: policeAdmin.php');
        exit();
    
    }elseif($pID > 0 and $pType !='police' ){
        header('location: Home.html');
        exit();
    
    }
    





if (@isset($_POST['reset_password'])) {
    // Retrieve current password, new password, and confirm new password from the form
    $currentPassword = @trim($_POST['currentPassword']);
    $newPassword = @trim($_POST['newPassword']);
    $confirmNewPassword = @trim($_POST['confirmNewPassword']);
    
    // Validate if the new password matches the confirm new password
    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['error_msg'] = "كلمات المرور غير متطابقة";
    } elseif (strlen($newPassword) < 8 || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/\d/", $newPassword) || !preg_match("/[!@#$%^&*()_+{}\[\]:;<>,.?/~]/", $newPassword))
    {
        $_SESSION['error_msg'] = "يجب أن تكون كلمة المرور على الأقل 8 أحرف وتحتوي على حرف كبير وحرف صغير ورقم واحد ورمزًا خاصًا على الأقل";
    } else {
        // Query to retrieve the stored password from the database
        $sql = "SELECT * FROM police WHERE Email='$pEmail'";
        $qur = $conn->query($sql);
        $row = $qur->fetch_assoc();
        $storedPassword=@$row['Password'];
        // Verify if the current password matches the stored password
        if (!password_verify($currentPassword, $storedPassword)) {
            $_SESSION['error_msg'] = "كلمة المرور الحالية غير صحيحة";
        } else {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the password in the database
            $sql = "UPDATE police SET Password='$hashedPassword' WHERE Email='$pEmail'";
            $qur = $conn->query($sql);
            
            if ($qur) {
                $_SESSION['error_msg'] = "تم تغيير كلمة المرور بنجاح";
            } else {
                $_SESSION['error_msg'] = "حدث خطأ أثناء إعادة تعيين كلمة المرور";
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
    <link rel="icon" href="logo.htmll.png">
    <title>نظام الدرج </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #9c27b0, #8ecdff);
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;

        }

        .white-section {
            background-color: white;
            padding: 100px;
            margin: 100px auto;
            height: 600px;
            width: 900px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;

        }

        .btn-container {
            display: flex;
            justify-content: end;
            margin-top: 0px;
            align-items: right;
        }

        .btn-containers {
            display: flex;
            justify-content: start;
            margin-top: 10px;
            align-items: left;


        }

        .btn-container button {
            width: 200px;
            height: 100px;

        }

        .btn-container button {
            width: 200px;
            height: 100px;

        }

        .btn-container dropdown-content {
            width: 200px;
            height: 100px;
        }

        .nav-page {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: (255, 255, 255, 0.8);
            z-index: 999;
            padding-top: 100px;
        }

        .home-container {
            max-width: 800px;
            margin: auto;
            margin-top: 20px;
        }

        .jumbotron {
            background-color: #bc73f8;
            color: #fff;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        #resetPasswordModal,
        #logoutModal {
            padding: 20px;
        }

        .logo {
            float: right;
            position: absolute;
            top: -16px;
            right: 20px;
            width: 100px;
            height: auto;
            z-index: 999;

        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            bottom: -140%;
            /* للخروج من أسفل الزر */
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>

<?php if (isset($_SESSION['error_msg'])){
    echo  "<script>alert('{$_SESSION['error_msg']}');</script>";
     unset($_SESSION['error_msg']);  
} 
     ?>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <img src="logo.html.png" alt="logo" style=" width:90px; height: 90px;" class="logo">
        <div class="navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">

            
            </ul>
        </div>

    </nav>
    <section class="white-section ">
        <div class="btn-containers">
            <!-- <button class="btn custom-btn" onclick="viewEvents()">عرض التقارير</button> -->
            <button class="btn custom-btn"> <a href="show_reports.php" class="custom-button">عرض البلاغات
                    الجديدة</a></button>
            <button class="btn custom-btn"> <a href="show_reportsAll.php" class="custom-button">عرض البلاغات
                    المنتهية</a></button>

            
        </div>
        <div class="container home-container">
            <div class="jumbotron">

                <h1 class="display-4"> مرحبا  <?=$pName?></h1>
                <p class="lead">إدارة وعرض البلاغات</p>
            </div>
        </div>
        <div class="btn-container">
            






            <div class="content">

            </div>
        </div>


        <!-- Bootstrap JS scripts -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <div class="row col-12 text-center" style="max-height: 80px;">

         
            
            
            <button  type="button" class="btn btn-primary col-4 mx-auto " style="background-color: #480082; border-color: #480082; height: 60px;" data-toggle="modal" data-target="#resetPasswordModal">
                إعادة تعيين كلمة المرور
            </button>
        
            
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-danger my-auto" onclick="openLogoutModal();">تسجيل خروج</button>
             
                
        </div>
           
        
<div class="modal" id="resetPasswordModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">إعادة تعيين كلمة المرور</h4>
              
            </div>
            <div class="modal-body">
               
                <form action="" method="post">
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
        <!-- Sample JavaScript functions (replace with your actual logic) -->
        <script>
    
            function openLogoutModal() {

                // Create a Bootstrap modal for logout confirmation
                const logoutModal = `
            <div class="modal" id="logoutModal"  style="text-align: right;">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">تسجيل خروج</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>هل تريد تسجيل الخروج؟</p>
                            <a type="button" class="btn btn-danger" href="logout.php"> موافق</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
                $('body').append(logoutModal);

                // Show the modal
                $('#logoutModal').modal('show');
            }

            function logout() {

                alert('Logout successful!');

                window.location.href = 'Home.html';
            }
        </script>
</body>

</html>