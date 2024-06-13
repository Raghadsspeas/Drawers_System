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
    }elseif($pID > 0 and $pType=='police'){
        header('location: policePage.php');
        exit();
        
    }elseif($pID > 0 and $pType !='officer' ){
        header('location: Home.html');
        exit();
        
    }
    
    
    
    if (isset($_POST['logout'])) {
        // Unset all session variables
        session_unset();
        // Destroy the session
        session_destroy();
        header("Location: logIn.php"); // Redirect to login page after logout
        exit();
    }
    
    
    
    $reportDate = @trim($_POST["reportDate"]);
    $reportID = @(int)$_POST["repID"];
    
    $filter ="where ReportID > 0";
    
    if($reportID >0 ){
        $reportDate ='';
        $filter ="where ReportID = $reportID  ";
    
    }
    
    $cardClass ='';

    if($reportDate !='') $filter .=" AND Time = '$reportDate' ";

    

if (isset($_POST['reset_password'])) {
    // Retrieve current password, new password, and confirm new password from the form
    $currentPassword = @trim($_POST['currentPassword']);
    $newPassword = @trim($_POST['newPassword']);
    $confirmNewPassword = @trim($_POST['confirmNewPassword']);
    
    // Validate if the new password matches the confirm new password
    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['error_msg'] = "كلمات المرور غير متطابقة";
    } elseif (strlen($newPassword) < 8 || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/\d/", $newPassword)) {
        $_SESSION['error_msg'] = "يجب أن تكون كلمة المرور على الأقل 8 أحرف وتحتوي على حرف كبير وحرف صغير ورقم واحد على الأقل";
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
                $_SESSION['update_pass'] = "تم تغيير كلمة المرور بنجاح";
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
    <title> الصفحة الرئيسية</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        direction: rtl;
        background: #e7e7e7;
    }

    select {
        float: right;
        /* تحريك القائمة المنسدلة إلى اليمين */
        margin-left: 20px;
        /* مسافة من اليمين لتفادي التداخل مع العناصر الأخرى */
    }

    input[type="submit"] {
        float: right;
        /* تحريك زر الإرسال إلى اليمين */
        margin-left: 10px;
        /* مسافة من اليمين لتفادي التداخل مع العناصر الأخرى */
    }

    .content {
        clear: both;
        /* تأكيد عدم تداخل العناصر مع القائمة المنسدلة وزر الإرسال */
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        text-align: center;
        margin-top: 50px;
    }

    .button-container {
        display: inline-block;
        margin-bottom: 20px;

    }

    .logout-button {
        margin-top: 20px;
    }

    h1 {
        text-align: center;
        background: linear-gradient(to right, #9c27b0, #8ecdff);
        color: white;
        padding: 50px;
    }

    .report {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 15px;
        cursor: pointer;
    }

    .report:hover {
        background-color: #aceedd;
    }

    .report.new {
        background-color: #aaa;
        color: white;
    }

    .report.new:hover {
        background-color: #bbc;
    }

    .report.selected {
        background-color: rgb(161, 161, 213);
        color: white;
    }

    .assign-button {
        display: inline-block;
        margin-left: 10px;
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .assign-button:hover {
        background-color: #45a049;
    }

    .navbar {
        background-color: #bbc;
        overflow: hidden;
        padding: 10px 0;
    }


    .navbar .list {
        width: 90%;
        margin: 10px;
    }


    .navbar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: space-around;
    }


    .navbar li {
        float: right;
    }


    .navbar li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }


    .navbar li a:hover {
        background-color: #ddd;
        color: black;
    }

    .lead {
        text-align: right;
        margin-top: 30px;
    }

    .table {
        text-align: center;

    }

    .table,
    td {
        border: 3px solid #99999955;
    }

    .cardID {
        width: 60px;
    }

    .cardName {
        width: auto;
    }

    .cardDate {
        width: 160px;
    }


.cardEnd{
    background-color: #ffbb00 !important;
}
</style>
    <script>
    function assignPolice(button, reportID) {
        var policeId = prompt("ادخل رقم الشرطي:");
        if (policeId) {
            var isAvailable = confirm("هل الشرطي متاح؟");
            var availability = isAvailable ? "1" : "0";
            if (isAvailable) {
                var url = "process.php?reportID=" + reportID + "&policeId=" + policeId + "&availability=" +
                availability;
                window.location.href = url;
            } else {
                alert("الشرطي غير متاح");
            }
        }
    }
    </script>



</head>

<body>
    
    <?php if (isset($_SESSION['error_msg'])){
    echo  "<script>alert('{$_SESSION['error_msg']}');</script>";
     unset($_SESSION['error_msg']);  
} 

$eyeicon ='<svg  xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
            <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
          </svg>';


$foldericon='<svg  xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16">
  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
  <path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
</svg>';


$policeicon = '<svg class="svg-icon" style="width: 32px; height: 32px;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M392.533333 409.6h34.133334c10.24 0 17.066667-6.826667 17.066666-17.066667s-6.826667-17.066667-17.066666-17.066666h-34.133334c-10.24 0-17.066667 6.826667-17.066666 17.066666s6.826667 17.066667 17.066666 17.066667zM597.333333 409.6h34.133334c10.24 0 17.066667-6.826667 17.066666-17.066667s-6.826667-17.066667-17.066666-17.066666h-34.133334c-10.24 0-17.066667 6.826667-17.066666 17.066666s6.826667 17.066667 17.066666 17.066667zM443.733333 740.693333V1024h136.533334v-283.306667c-23.893333 6.826667-44.373333 10.24-68.266667 10.24s-47.786667-3.413333-68.266667-10.24zM296.96 655.36l-174.08 71.68c-47.786667 17.066667-78.506667 58.026667-88.746667 105.813333l-34.133333 170.666667c0 3.413333 0 10.24 3.413333 13.653333 3.413333 3.413333 10.24 6.826667 13.653334 6.826667H409.6v-290.133333-3.413334c-34.133333-17.066667-68.266667-37.546667-95.573333-71.68-3.413333-3.413333-10.24-6.826667-17.066667-3.413333zM989.866667 832.853333c-10.24-47.786667-40.96-88.746667-88.746667-105.813333l-174.08-71.68c-6.826667-3.413333-13.653333 0-20.48 3.413333-27.306667 30.72-61.44 54.613333-95.573333 68.266667V1024h392.533333c3.413333 0 10.24-3.413333 13.653333-6.826667s3.413333-10.24 3.413334-13.653333l-30.72-170.666667z m-204.8 37.546667c0 3.413333-3.413333 10.24-3.413334 13.653333l-34.133333 34.133334c-3.413333 3.413333-10.24 3.413333-13.653333 3.413333s-10.24 0-13.653334-3.413333l-34.133333-34.133334c-3.413333-3.413333-3.413333-6.826667-3.413333-13.653333v-34.133333c0-10.24 6.826667-17.066667 17.066666-17.066667h68.266667c10.24 0 17.066667 6.826667 17.066667 17.066667v34.133333zM812.373333 204.8c3.413333-6.826667 6.826667-17.066667 6.826667-27.306667V136.533333c0-10.24-6.826667-17.066667-17.066667-17.066666-23.893333 0-64.853333-23.893333-105.813333-47.786667C638.293333 37.546667 573.44 0 512 0s-126.293333 37.546667-184.32 71.68c-40.96 23.893333-81.92 47.786667-105.813333 47.786667-10.24 0-17.066667 6.826667-17.066667 17.066666v51.2c0 6.826667 0 10.24 3.413333 17.066667h604.16zM477.866667 85.333333c0-10.24 6.826667-17.066667 17.066666-17.066666h34.133334c10.24 0 17.066667 6.826667 17.066666 17.066666v20.48c0 30.72-27.306667 30.72-34.133333 30.72s-34.133333 0-34.133333-30.72v-20.48z" fill="" /><path d="M266.24 498.346667c37.546667 126.293333 139.946667 218.453333 242.346667 218.453333s197.973333-85.333333 235.52-215.04c34.133333 0 61.44-44.373333 61.44-102.4 0-47.786667-17.066667-85.333333-44.373334-98.986667v-10.24c0-17.066667-3.413333-34.133333-6.826666-54.613333v-3.413333H262.826667v3.413333c0 6.826667-3.413333 10.24-6.826667 13.653333-3.413333 10.24-10.24 20.48-10.24 34.133334v6.826666c-23.893333 20.48-40.96 58.026667-40.96 105.813334 0 58.026667 27.306667 102.4 61.44 102.4z m-3.413333-170.666667s3.413333 0 0 0c6.826667 0 10.24 0 13.653333-3.413333s6.826667-10.24 3.413333-13.653334v-6.826666-13.653334c0-3.413333 0-10.24 3.413334-13.653333C320.853333 307.2 392.533333 341.333333 512 341.333333c105.813333 0 174.08-27.306667 211.626667-58.026666V314.026667c0 3.413333 0 10.24 3.413333 13.653333 3.413333 3.413333 6.826667 6.826667 13.653333 6.826667h3.413334c10.24 3.413333 23.893333 27.306667 23.893333 68.266666s-17.066667 68.266667-27.306667 68.266667h-3.413333c-3.413333-3.413333-10.24-3.413333-17.066667 0-3.413333 3.413333-10.24 6.826667-10.24 10.24-30.72 122.88-112.64 204.8-204.8 204.8-95.573333 0-184.32-88.746667-215.04-208.213333 0-6.826667-3.413333-10.24-10.24-10.24-3.413333-3.413333-10.24 0-17.066666 0h-3.413334c-10.24 0-27.306667-27.306667-27.306666-68.266667 6.826667-44.373333 23.893333-68.266667 30.72-71.68z" fill="" /></svg>';

$endicon1 = '<svg xmlns="http://www.w3.org/2000/svg"  width="80" height="80" viewBox="0 0 172 172" style=" fill:#26e07f;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#1fb141"><path d="M21.5,21.5v129h64.5v-32.25v-64.5v-32.25zM86,53.75c0,17.7805 14.4695,32.25 32.25,32.25c17.7805,0 32.25,-14.4695 32.25,-32.25c0,-17.7805 -14.4695,-32.25 -32.25,-32.25c-17.7805,0 -32.25,14.4695 -32.25,32.25zM118.25,86c-17.7805,0 -32.25,14.4695 -32.25,32.25c0,17.7805 14.4695,32.25 32.25,32.25c17.7805,0 32.25,-14.4695 32.25,-32.25c0,-17.7805 -14.4695,-32.25 -32.25,-32.25z"></path></g></g></svg>';
//$endicon = '<svg fill="#000000" height="32px" width="32px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 	 viewBox="0 0 493.004 493.004" xml:space="preserve"><g id="XMLID_414_">	<path id="XMLID_419_" d="M441.934,51.128H221.175l-8.914-17.2c-8.207-18.388-26.482-30.245-46.634-30.245H51.072		C22.872,3.682,0,26.547,0,54.77v332.922c0,24.902,20.185,45.086,45.095,45.086h210.257c-0.201-4.459-0.521-8.743-0.521-13.511		c0-49.081,10.951-82.438,30.749-96.029v-14.041c0-57.521,46.805-104.324,104.334-104.324c52.364,0,95.718,38.83,103.09,89.18		v-191.87C493.004,73.976,470.14,51.128,441.934,51.128z M441.709,141.013H51.305V54.994h114.178		c0.385,0.867,0.802,1.717,1.244,2.584l8.919,17.167c8.825,17.008,26.379,27.662,45.529,27.662h220.534V141.013z"/>	<path id="XMLID_415_" d="M471.634,349.248h-10.261v-40.051c0-39.404-32.059-71.465-71.458-71.465		c-39.414,0-71.473,32.061-71.473,71.465v40.051h-10.277c-11.311,0-20.472,31.35-20.472,70.02c0,38.702,9.161,70.055,20.472,70.055		h81.382h0.713h81.375c11.31,0,20.474-31.353,20.474-70.055C492.107,380.598,482.944,349.248,471.634,349.248z M408.359,451.151		c0,5.681-4.606,10.284-10.278,10.284h-16.375c-5.671,0-10.276-4.604-10.276-10.284v-46.95c0-5.68,4.605-10.283,10.276-10.283		h16.375c5.672,0,10.278,4.603,10.278,10.283V451.151z M427.614,349.248h-37.355h-0.713h-37.329v-40.051		c0-20.794,16.896-37.705,37.697-37.705c20.788,0,37.7,16.911,37.7,37.705V349.248z"/></g></svg>';
$endicon = '';

$eyetitle ="جديد" ; 
$foldertitle ="تم الاستلام "  ;
$policetitle ="عند العسكري" ;
$endtitle ="تم الإنتهاء " ;




     ?>


    <div class="container" style=" margin-bottom: 60px; ">
        <h1 class="display-4"> مرحبا <?php echo $pName; ?> </h1>
        <p class="lead"> إدارة البلاغات</p>

        <!-- Display Report Details -->
        <?php
         if (isset($_SESSION['update_msg'])){

        echo "<p>{$_SESSION['update_msg']}</p>";
    unset($_SESSION['update_msg']);  
         }

         if (isset($_SESSION['update_pass'])){

        echo "<p>{$_SESSION['update_pass']};</p>";
         unset($_SESSION['update_pass']); 
    
        } ?>
        
        <form action="" method="post">
            <select name="reportDate" id="reportDate" style="float: right; margin-bottom: 20px;"
            onchange='this.form.submit()'>
            <option value="">الكل</option>
            <?php
    // استعلام SQL لاسترداد جميع السجلات من قاعدة البيانات
    $sql = "SELECT DISTINCT Time FROM reports";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // استخراج التاريخ من السجل
            $date = $row['Time'];
            $sel = ($reportDate == $date)?' selected ':'' ;
            // عرض التاريخ في القائمة المنسدلة
            echo "<option value='" . $date . "'  $sel >" . $date . "</option>";
        }
    }
    ?>
            </select>
            <input type="submit" value="عرض" style="float: left; margin-bottom: -20px;">
             <input type="text" value="" name="repID" style="float: left; margin-bottom: -20px;"> 

        </form>
        <div class="content">
            <?php
    $sql = "SELECT * FROM reports left join police on (EmployeeID = policeId ) $filter";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // عرض البيانات بشكل ديناميكي داخل عنصر HTML
        
        


        
        while ($row = $result->fetch_assoc()) {
            $cardClass ='';
            $icon = $eyeicon ;
            $stat = @trim($row['acceptance_status']);
            if ($stat == 'جديد') { $icon = $eyeicon ; 
                $title1 = $eyetitle ; 
            }
            if ($row['EmployeeID']  > 0 ) { $icon = $policeicon ; 
                $title1 = $policetitle ; 
            }
            if ($row['EmployeeID']  == 0 and  $stat != 'جديد') { $icon = $foldericon ; 
                $title1 = $foldertitle ; 
            }
            if ($stat == 'تم الإنتهاء' ) {
                $icon = $endicon ; 
                $title1 = $endtitle ; 
    $cardClass ='style="background-color: #ccc;"';
}

            
echo "<div class='report' $cardClass>";
echo "<table class='table'>";
echo "<tr>";
echo "<td class='cardID'>$row[ReportID]</td>";
echo "<td class='cardName'>$row[Name]</td>";
echo "<td class='cardDate'>$row[Time]</td>";
echo "</tr>";
echo "<tr>";
echo "<td class='cardt1'></td>";
echo "<td class='cardt1' title='$title1'>$icon</td>";
echo "<td class='cardt1'>";

    echo "<a class='assign-button' href='repShow.php?id=$row[ReportID]&action=open'>فتح البلاغ</a>";

echo "</td>";

echo "</tr>";
echo "</table>";
echo "</div>";

        }
    } else {
        echo "لا توجد بيانات";
    }
    
    ?>

        </div>

        <?php


    if (isset($_POST['submit'])) {
        $reportId = $_POST['reportId'];
        
        $reportDate = $_POST['reportDate'];

        // التحقق من تحميل الملف
        if (isset($_FILES['reportPhoto'])) {
            
            $targetDir = "D:/xampp/htdocs/police/uploads/";
            $targetFile = $targetDir . basename($reportPhoto);

            // نقل الملف المحمل إلى المجلد المستهدف
            if (move_uploaded_file($_FILES['reportPhoto']['tmp_name'], $targetFile)) {
                // قم بإدخال البيانات في جدول "reports"
                  
                $sql = "INSERT INTO reports (ReportID, Time) VALUES ('$reportId', '$reportDate')";

                if ($conn->query($sql) === TRUE) {
                    echo "تمت إضافة البيانات بنجاح.";
                } else {
                    echo "حدث خطأ أثناء إضافة البيانات: " . $conn->error;
                }
            } else {
                echo "حدث خطأ أثناء تحميل الملف.";
            }
        }
    }

    $conn->close();
    ?>



        <!-- سكريبت JavaScript للتحكم في فتح وإغلاق العنصر المنبثق -->
        <script>
        var openModalBtn = document.getElementById("openModalBtn");
        var modal = document.getElementById("modal");

        openModalBtn.addEventListener("click", function() {
            modal.style.display = "block";
        });

        var closeBtn = document.querySelector(".modal-content .close");
        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });
        </script>






        <script>
        var modal = document.getElementById("modal");




        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function toggleReport(report) {
            report.classList.toggle("selected");
            report.classList.remove("new");
        }
        </script>
        <!-- Update Information Button -->
        <!-- <button type="button" class="btn btn-primary" style="background-color: #480082; border-color: #480082; margin-top: 30px;"data-toggle="modal" data-target="#userInfoModal">
        عرض / تحديث معلوماتي
    </button> -->
        <button type="button" class="btn btn-primary"
            style="background-color: #480082; border-color: #480082; margin-top: 30px;" data-toggle="modal"
            data-target="#resetPasswordModal">
            إعادة تعيين كلمة المرور
        </button>

        <div class="modal" id="resetPasswordModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">إعادة تعيين كلمة المرور</h4>

                    </div>
                    <div class="modal-body">

                        <form action="" method="post">
                            <div class="form-group" style="text-align: right;">
                                <label for="currentPassword"> كلمة المرور الحالية: </label>
                                <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                    required>
                            </div>
                            <div class="form-group" style="text-align: right;">
                                <label for="newPassword"> كلمة مرور جديدة: </label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword"
                                    required>
                            </div>
                            <div class="form-group" style="text-align: right;">
                                <label for="confirmNewPassword"> تأكيد كلمة المرور الجديدة: </label>
                                <input type="password" class="form-control" id="confirmNewPassword"
                                    name="confirmNewPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="reset_password"> إعادة تعيين كلمة
                                المرور</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <form id="logoutForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <button type="submit" class="btn btn-danger" style="margin-top: 20px;">
                <i class="fas fa-sign-out-alt"></i> تسجيل خروج
            </button>
            <input type="hidden" name="logout">
        </form>

        


    <!-- Bootstrap JS scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>