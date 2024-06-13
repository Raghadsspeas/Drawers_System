<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drawersystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pID = @(int)$_SESSION['policeId'];

if ($pID == 0) {
    header('location: logIn.php');
    exit;
}

$sql2 = "SELECT * FROM police WHERE EmployeeID=$pID";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
    $pName = $row2['Name'];
} else {
    header('location: logInP.php');
    exit;
}

$sql = "SELECT * FROM police_permissions WHERE policeId=$pID";
$result = $conn->query($sql);

$permissions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $permissions[$row['ReportID']] = $row;
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
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background: linear-gradient(to right, #9c27b0, #8ecdff);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        .report {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ddd;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .report:hover {
            background-color: #aaf;
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
            background-color:#9932CC;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .assign-button:hover {
            background-color: #6699ff;
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
    </style>
</head>

<body>
<div class="container">
        <h2><center><?= $pName; ?></center></h2>
        <?php
        // Fetch reports
        $reports_query = "SELECT * FROM reports";
        $reports_result = $conn->query($reports_query);

        if ($reports_result->num_rows > 0) {
            while ($report = $reports_result->fetch_assoc()) {
                // Display buttons if permissions exist for this report
                if (isset($permissions[$report['ReportID']])) {
                    $permission = $permissions[$report['ReportID']];
                    echo "<div class='report'>";
        
                    // Check if view permission is granted
                    if ($permission['can_view'] == 1) {
                        echo '<div class="content">';
                        echo "<h2>بيانات التقرير</h2>";
                        echo "<p>رقم التقرير: {$report['ReportID']}</p>";
                        echo "<p>التاريخ والوقت : {$report['Time']}</p>";
                        echo "<p>الرقم التسلسلي: {$report['MACAddress']}</p>";
                        echo "<hr><p>ملاحظات: <br>{$report['Note']}</p>";
                        echo "<hr>"; // Display activated view checkbox
        
                        // Check if edit permission is granted
                        if ($permission['can_edit'] == 1) {
                            echo "<a href='process_review.php?report_id={$report['ReportID']}' class='assign-button'>تعديل التقرير </a>";
                        }
        
                        // Check if print permission is granted
                        if ($permission['can_print'] == 1) {
                            echo "<a href='printReport.php?report_id={$report['ReportID']}' class='assign-button' target='_print'> طباعة التقرير </a>";
                        }
        
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
        } else {
            echo "<p>لا يوجد تقارير متاحة حاليًا.</p>";
        }
        
        ?>
    </div>
</body>

</html>
