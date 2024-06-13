<?php
include "connect.php";
// تحقق من القيم المرسلة عبر GET
if (isset($_GET["reportID"]) && isset($_GET["policeId"])) {
    $reportID = $_GET["reportID"]; // استقبل reportID بواسطة GET
    $policeId = $_GET["policeId"]; // استقبل policeId بواسطة GET

    // الآن يمكنك استخدام قيمة reportID و policeId في استعلام SQL لتحديث السجل المطلوب
    // تأكد من تنفيذ عمليات التحقق والتطهير للبيانات لتجنب هجمات الحقن

    // تحديث قيمة حقل policeId في جدول reports
    $sql = "UPDATE reports SET policeId = '$policeId' WHERE ReportID = '$reportID'"; // استخدم reportID في التحديث

    if ($conn->query($sql) === TRUE) {
        // تم التحديث بنجاح، يمكننا توجيه المستخدم إلى الصفحة المطلوبة
        $sql2 = "UPDATE police    SET availability='غير متاح' WHERE EmployeeID = '$policeId'"; // تحويل العسكري لغير متاح

        $conn->query($sql2) ;

        header("Location: policeAdmin.php");
        exit(); // تأكد من إيقاف تشغيل السكريبت بعد توجيه المستخدم
    } else {
        echo "حدث خطأ أثناء تحديث رقم الشرطي: " . $conn->error;
    }

    // إغلاق الاتصال بقاعدة البيانات
} else {
    // في حالة عدم وجود reportID أو policeId في الرابط
    echo "لم يتم تحديد معرف التقرير أو رقم الشرطي.";
}
$conn->close();
?>
