<?php
include "connect.php";

// استعلام SQL لاسترداد البلاغات مع تواريخها
$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

$reports = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $report = [
            'Time' => $row['Time'],
            
            // يمكنك إضافة المزيد من الحقول هنا حسب الحاجة
        ];
        $reports[] = $report;
    }
}

// إغلاق الاتصال
$conn->close();

// إرجاع البيانات كمصفوفة JSON
header('Content-Type: application/json');
echo json_encode($reports);
?>