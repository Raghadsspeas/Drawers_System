<?php
include "connect.php";

if(isset($_POST['note']) && isset($_POST['report_id'])  ) {
 
    // استقبال البيانات من النموذج
    $note = $_POST['note'];
    $report_id = $_POST['report_id'];

    $sql = "UPDATE reports SET note = '$note' ,acceptance_status = 'تم الإنتهاء' WHERE ReportID = '$report_id' ";
    
    if ($conn->query($sql) === TRUE) {
        
        // البحث عن العسكر الذين لديهم قضايا لم تنتهي وتحويلهم لغير متاحين 
        reActive();   
      header("Location: PolicePage.php");
        exit;
    } else {
        echo "حدث خطأ أثناء تحديث الملاحظة: " . $conn->error;
    }
    
}  


$report_id = @(int)$_GET['report_id'] ;
if( $report_id == 0 ) {
    header("Location: PolicePage.php");
    exit;
    
    
}else{
    $repText = '';
    $sql = "select * from  reports WHERE ReportID = '$report_id' ";
    
    $result=  $conn->query($sql) ;
   if ($result->num_rows > 0) {
       $row = $result->fetch_assoc(); 
       $repText = @trim($row["Note"]);
}
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معالجة المراجعة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            margin-top: 50px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
        }
        .content {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> التقرير</h1>
        <div class="content">
            <!-- نموذج إدخال الملاحظات -->
            <form action="process_review.php" method="post">
                <label for="note">الملاحظة:</label><br>
                <textarea id="note" name="note" placeholder="أدخل الملاحظة هنا..."><?php  echo $repText?></textarea><br>
                <!-- إرسال معرف التقرير كقيمة مخفية -->
                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                <button type="submit">حفظ الملاحظة</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php

function reActive(){
global $conn;

    $conn->query("UPDATE police  SET availability='متاح' "); // تحويل جميع العسكر ل  متاح
    $sql2 = "SELECT count(*) as repCount ,policeId FROM reports  where acceptance_status <> 'تم الإنتهاء'  group by policeId";
    $result2 = $conn->query($sql2);
    // print_r($result2);
    // exit;
    if ($result2->num_rows > 0) {
        
        while ($row = $result2->fetch_assoc()) {
            
            if ($row['repCount'] > 0) {
                $conn->query("UPDATE police  SET availability='غير متاح' WHERE EmployeeID = $row[policeId] ;"); // تحويل العسكري لغير متاح
            } 
            
        }
        
    }
}
        
        ?>