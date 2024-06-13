<?php
// Database configuration
include "connect.php";

$id= @(int)$_GET['id'];
if ($id == 0) {
  header('location: policeAdmin.php');
  exit;

}

// Assuming you have already established a database connection

if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'open') {
    // Get the ID from the query string
    $reportID = $_GET['id'];
    
    // Perform the database update
    $sql = "UPDATE reports SET acceptance_status = 'تم الاستلام' WHERE ReportID = $reportID and acceptance_status <> 'تم الإنتهاء'";
    
    if(mysqli_query($conn, $sql)) {
        
    } else {
        // Error handling
        echo "Error updating report: " . mysqli_error($conn);
    }
} else {
    // Handle invalid request
    echo "Invalid request";
}
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
      margin: 10px;
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
        
  </style>
</head>

<body>
  <div class="container">
    <h1>عرض البلاغات</h1>
    <div class="content">
      <?php
      // استعلام SQL لعرض بيانات التقرير المحدد
      $result = $conn->query("SELECT * FROM reports WHERE ReportID = $id");
    $result2 = $conn->query($sql);

      if ($result->num_rows > 0) {
     $row = $result->fetch_assoc(); 
          echo "<h2>بيانات التقرير</h2>";
          echo "<p>معرف التقرير: {$row['ReportID']}</p>";
          echo "<p>التاريخ والوقت: {$row['Time']}</p>";
      
          echo "<p>ملاحظة: {$row['Note']}</p>";
          echo "<p>عنوان MAC: {$row['MACAddress']}</p>";
          

          // إضافة نموذج HTML لتحديث حالة القبول
          echo "<form action='process_acceptance.php' method='post'>";
          echo "<input type='hidden' name='reportID' value='{$row['ReportID']}'>";
          
          
          // echo "<a href='process_review.php?report_id={$row['ReportID']}' class='edit-note-button'> التقرير</a>";
          
          if ($row['acceptance_status'] != 'تم الإنتهاء' and @(int)$row['policeId'] ==0 ){

            $sql2 = "SELECT * FROM police WHERE availability = 'متاح' AND Position = 'police'";
            
            $result2 = $conn->query($sql2);
            
            // Check if there are available police officers
            if ($result2->num_rows > 0) {
              // Display available police officers
              echo "<h2>تعيين شرطي</h2>";
              while ($row2 = $result2->fetch_assoc()) {
                $des1 = getDis($row2['EmployeeID'], $row['MACAddress']);
                echo "<a href ='process.php?reportID=$id&policeId=$row2[EmployeeID]' class='btn'>$row2[EmployeeID] - $row2[Name] </a> المسافة :- $des1 كلم <br>";

              }
            } else {
              // Display message when no police officer is available
              echo "<hr> <span class='btn'>لا يوجد شرطي متاح</span>";
            }
            
            
          }
            
            echo "</form>";
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

<?php

function getDis($pID =0 , $mac="") {
global $conn ;
$sql = "SELECT p.Code, ps.Address FROM Police p  INNER JOIN Policestation ps ON p.Code = ps.Code  WHERE p.EmployeeID = $pID ";
$result = $conn->query($sql);
$pLat = 0; // Set default latitude
$pLng = 0; // Set default longitude

  if ($result->num_rows > 0) {
     $row = $result->fetch_assoc() ;
    $address = $row["Address"];
    $coordinates = explode(",", $address);
if (count($coordinates) >= 2) {
    // Remove parentheses from latitude and longitude values
    $pLat = str_replace('(', '', trim($coordinates[0]));
    $pLng = str_replace(')', '', trim($coordinates[1]));
} 
  }
//*************************** */
$sql2 = "SELECT o.Address FROM ownert o  INNER JOIN reports r ON r.MACAddress = o.MACAddress";
$result2 = $conn->query($sql2);
$oLat = 0; // Set default latitude
$oLng = 0; // Set default longitude

  if ($result2->num_rows > 0) {
     $row2 = $result2->fetch_assoc() ;
    $address2 = $row2["Address"];
    $coordinates2 = explode(",", $address2);
if (count($coordinates2) >= 2) {
    // Remove parentheses from latitude and longitude values
    $oLat = str_replace('(', '', trim($coordinates2[0]));
    
    $oLng = str_replace(')', '', trim($coordinates2[1]));
    
} 

// echo $oLat ;

//  echo $oLng ;

}

$dLat = $oLat - $pLat ;

$dLng = $oLng - $pLng ;


$zoom = 100 ;

$Dist1 = @sqrt(pow($dLat,2) + pow($dLng,2));
// echo 'مسافة الشرطي على ' . $dLat;
$Dist = @number_format($Dist1 * $zoom ,2,',','.');
// echo' <br>';
// echo ' مسافة بين الشرطي محمد ' . $Dist;

if ( $oLat ==0 or  $pLat ==0 or  $oLng == 0 or  $pLng == 0 ) {

  $Dist = 'لا يمكن حساب المسافة بالـ' ;

}


return  $Dist ;
  

  }

?>