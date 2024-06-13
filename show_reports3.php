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



    .accept-button {
      background-color: #4CAF50;
      border: none;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      transition-duration: 0.4s;
      cursor: pointer;
      border-radius: 8px;
      width: 100%;
    }

    .accept-button:hover {
      background-color: #45a049;
    }


    .edit-note-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    width: 93%;
}

.edit-note-button:hover {
    background-color: #45a049;
}



  </style>



</head>

<body>
  <div class="container">
    <h1>عرض البلاغات</h1>
    <div class="content">
      <?php
      // استعلام SQL لعرض بيانات التقرير المحدد
      $$result = mysqli_query($conn, "
      SELECT reports.* ,police.Name
      FROM reports 
      LEFT JOIN police ON reports.policeId = police.EmployeeID
      WHERE reports.ReportID = $report_id
      AND policeId != 0");

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<h2>بيانات التقرير</h2>";
          echo "<p>معرف التقرير: {$row['ReportID']}</p>";
          echo "<p>الوقت: {$row['Time']}</p>";
          echo "<p>الشرطي:{$row['policeId']}</p>";
          echo "<p>ملاحظة: {$row['Note']}</p>";
          
          echo "<p>عنوان MAC: {$row['MACAddress']}</p>";
          
          echo "<a href='process_review.php?report_id={$row['ReportID']}' class='edit-note-button'>تعديل التقرير </a>";
          echo "<a href='printReport.php?report_id={$row['ReportID']}' class='edit-note-button' target='_print'> طباعة التقرير </a>";
          echo"_____________________________________________________";



        }
      } else {
        echo "<p>لا يوجد تقارير متاحة حاليًا.</p>";
      }
      ?>
    </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", function() {
  var editNoteButtons = document.querySelectorAll(".edit-note-button");
  
  editNoteButtons.forEach(function(button) {
    button.addEventListener("click", function() {
      // قم بفتح نافذة نموذج لتعديل الملاحظة
      // يمكنك استخدام صندوق حوار أو عنصر div قابل للتحرير أو غيرها
      // عند الانتهاء من تعديل الملاحظة، قم بإرسال البيانات إلى الخادم للتحديث باستخدام POST
    });
  });
});
</script>
</body>

</html>