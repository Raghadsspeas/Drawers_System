<?php
// Database configuration
include "connect.php";


$id= @(int)$_GET['report_id'];
if ($id == 0) {
  header('location: show_reportsAll.php');
  exit;

}


?>
<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>طباعة تقرير </title>

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
      $result = mysqli_query($conn, "
          SELECT * FROM reports WHERE ReportID = $id");

      if ($result->num_rows > 0) {
     $row = $result->fetch_assoc(); 
          echo "<h2>بيانات التقرير</h2>";
          echo "<p>رقم التقرير: {$row['ReportID']}</p>";
          echo "<p>الوقت: {$row['Time']}</p>";
          
          
          echo "<p>عنوان MAC: {$row['MACAddress']}</p>";
          echo "<hr><p>نص التقرير : <br> {$row['Note']}</p>";

      } else {
        header('location: show_reportsAll.php');
        exit;
      }
      ?>
    </div>
  </div>

  <script>
window.print();
</script>
</body>

</html>