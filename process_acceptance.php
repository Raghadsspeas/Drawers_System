<?php
// تحقق من البيانات المرسلة عبر POST
include "connect.php";

if(isset($_POST['reportID']) && isset($_POST['acceptance'])) {
    // تضمين معلومات الاتصال بقاعدة البيانات

    // استقبال البيانات من النموذج
    $reportID = $_POST['reportID'];

    // تحديث حالة القبول إلى "تم القبول"
    $sql = "UPDATE reports SET acceptance_status = 'تم القبول' WHERE ReportID = '$reportID'";

    if ($conn->query($sql) === TRUE) {
        // إغلاق الاتصال بقاعدة البيانات
        $conn->close();
        
        // التوجيه إلى صفحة عرض البلاغات بعد التحديث بنجاح
        header("Location: show_reports.php");
        exit(); // تأكد من توقف تشغيل النص بعد التوجيه
    } else {
        echo "حدث خطأ أثناء تحديث حالة القبول: " . $conn->error;
    }

    // إغلاق الاتصال بقاعدة البيانات
    $conn->close();
} else {
    // في حالة عدم وجود البيانات المرسلة عبر POST
    echo "لا توجد بيانات مرسلة.";
}
?>
