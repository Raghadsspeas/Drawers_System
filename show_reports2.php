<?php
// Database configuration
include "connect.php";


?>



<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>عرض البلاغات</title>

  <style>
    body {
      background: linear-gradient(to right, #9c27b0, #8ecdff); 
      direction: rtl;
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .content {
      background: #eee;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }

    .btn {
      display: inline-block;
      padding: 8px 12px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      margin-right: 10px;
    }

    .btn-danger {
      background-color: #dc3545;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>عرض البلاغات</h1>
    <div class="content">

    <?php
// تحقق من وجود قيمة التاريخ المحددة عبر POST
if(isset($_POST['reportID'])) {
    // استقبال قيمة التاريخ من النموذج
    $selected_date = $_POST['reportID'];

    // استعلام SQL لاسترداد السجلات الخاصة بالتاريخ المحدد
    $sql = "SELECT * FROM reports left join police on (EmployeeID = policeId ) WHERE Time = '$selected_date'";
    $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<h2>بيانات التقرير</h2>";
          echo "<p>معرف التقرير: {$row['ReportID']}</p>";
          echo "<p>الوقت: {$row['Time']}</p>";
          
          
          echo "<p>عنوان MAC: {$row['MACAddress']}</p>";
          echo "<p>الشرطي: {$row['Name']}</p>";
          // echo "<a href='edit_report.php?id=".$row['Date']."' class='btn btn-danger'>قبول</a>";
          // echo "<a href='delete_report.php?id=".$row['Date']."' class='btn'>انهاء</a>";

        echo "_________________________________________________________________";
        }
      } else {
        echo "<p>لا يوجد تقارير متاحة حاليًا.</p>";
      }}
      ?>
    </div>
  </div>
</body>
</html>
