<?php
include "connect.php";

?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.htmll.png">
    <title>نظام الدرج </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
         body {
            background: linear-gradient(to right, #9c27b0, #8ecdff); 
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
            display: flex;
            min-height: 100vh; 
            flex-direction: column; 
           
        }
         .white-section {
            background-color: white;
            padding: 100px;
            margin: 100px auto;
            height: 300px;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            
        }
        .btn-container {
            display: flex;
            justify-content: end;
            margin-top: 0px;
            align-items: right; 
        }
        .btn-containers {
            display: flex;
            justify-content: start;
            margin-top: 10px;
            align-items: left; 
           

        }
       
        .btn-container button {
            width: 200px;
            height: 100px;

        }
        .btn-container button {
            width: 200px;
            height: 100px;

        }    
           
        .btn-container dropdown-content {
            width: 200px;
            height: 100px;
        }
        .nav-page{
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: (255, 255, 255, 0.8);
            z-index: 999;
            padding-top: 100px;
        }
         .home-container {
            max-width: 800px;
            margin: auto;
            margin-top: 20px;
        }

        .jumbotron {
            background-color: #bc73f8;
            color: #fff;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        #resetPasswordModal,
        #logoutModal {
            padding: 20px;
        }
        .logo{
            float: right;
            position: absolute;
            top: -16px;
            right: 20px;
            width: 100px;
            height: auto;
            z-index: 999;
            
        }   
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            bottom: -140%; /* للخروج من أسفل الزر */
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
   <img src="logo.html.png" alt="logo" style=" width:90px; height: 90px;" class="logo">
    <div class="navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        
            <li class="nav-item" style="margin-top: auto;">
      
                <i class="bi bi-box-arrow-right"></i>
              <a style="color: red;"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16" onclick="openLogoutModal()">
  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="font-size: 18px; color: black;" onClick=openResetPasswordModal()>إعادة تعيين كلمة المرور</a>
            </li>
        </ul>
    </div>
    
</nav>
    <section class="white-section ">
        <div class="btn-containers">
        <!-- <button class="btn custom-btn" onclick="viewEvents()">عرض التقارير</button>
        <button class="btn custom-btn" onclick="viewUpdateInformation()" style="background-color:#f2f2f2; color:#212832;">  تحديث المعلومات</button>
     -->
    </div>
        <div class="container home-container">
    <!-- <div class="jumbotron">
        
        <h1 class="display-4">!مرحبا, عضو الشرطة</h1>
        <p class="lead">إدارة وعرض انظمة الدرج</p>
    </div> -->
        
        <div class="btn-container" >
    <!-- <form action="show_reports.php" method="post">
    <select name="reportID" id="reportDropdown" class="text-center">                                       
      <option value="">اختر البلاغ</option>
      <button class="btn custom-btn dropdown-toggle" onclick="toggleDropdown()"> اختر البلاغ </button>
      <div id="reportDropdown" class="dropdown-content">
        </div>

      <option value="2024-03-07">2024-03-07</option><option value="2024-02-06">2024-02-06</option><option value="2024-03-14">2024-03-14</option><option value="2024-03-10">2024-03-10</option>    </select>
    <input type="submit" value="عرض">
    </form> -->












    <form action="show_reports2.php" method="post">
  <select name="reportID" id="reportID">                                       
    <option value="">اختر التقرير</option>
    <?php
    // استعلام SQL لاسترداد جميع السجلات من قاعدة البيانات
    $sql = "SELECT DISTINCT Time FROM reports";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // استخراج التاريخ من السجل
        $date = $row['Time'];
        // عرض التاريخ في القائمة المنسدلة
        echo "<option value='" . $date . "'>" . $date . "</option>";
      }
    }
    ?>
  </select>
  <input type="submit" value="عرض">
</form>

<div class="content">
  <?php
  // التحقق من وجود البيانات المرسلة عبر POST
  if (isset($_POST["reportID"])) {
    $reportID = $_POST["reportID"];
    
    // استعلام SQL لعرض جميع السجلات المتعلقة بالتاريخ المحدد
    $sql = "SELECT * FROM reports WHERE Time = '$reportID'";
    // $sql = "SELECT * FROM reports WHERE Date = '$reportID'";

    $result = $conn->query($sql);
   
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<h2>بيانات التقرير</h2>";
        echo "<p>معرف التقرير: {$row['ReportID']}</p>";
        echo "<p>التاريخ والوقت: {$row['Time']}</p>";
       
        echo "<p>عنوان MAC: {$row['MACAddress']}</p>";
        echo "<a href='edit_report.php?id=".$row['Date']."' class='btn btn-danger'>قبول</a>";
        echo "<a href='delete_report.php?id=".$row['Date']."' class='btn'>انهاء</a>";
        echo "<hr>"; // خط فاصل بين كل تقرير والآخر
      }
    } else {
      echo "<p>لا توجد سجلات لهذا التاريخ.</p>";
    }

  }
  ?>
</div>
</div>





















    
   
        <!-- Bootstrap JS scripts -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Sample JavaScript functions (replace with your actual logic) -->
<script>
    function toggleDropdown() {
  document.getElementById("reportDropdown").classList.toggle("show");
}

// إغلاق القائمة المنسدلة إذا نقرت خارجها
window.onclick = function(event) {
  if (!event.target.matches('.btn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

function viewEvents() {
        // Assume you have an array of events for demonstration
        const events = [
            {
                reportId: '12345',
                macAddress: '00:11:22:33:44:55',
                date: '2024-01-20',
                time: '14:30:00',
                photo: 'path/to/photo1.jpg',
            },
            {
                reportId: '67890',
                macAddress: 'AA:BB:CC:DD:EE:FF',
                date: '2024-01-21',
                time: '16:45:00',
                photo: 'path/to/photo2.jpg',
            },
            // Add more events as needed
        ];

        // Create a Bootstrap modal for event details
        let modalContent = '<h5>تفاصيل الحدث</h5>';
        for (const event of events) {
            modalContent += `
                <div class="card mb-3">
                    <img src="${event.photo}" class="card-img-top" alt="Event Photo">
                    <div class="card-body">
                        <p class="card-text">رقم التقرير: ${event.reportId}</p>
                        <p class="card-text">عنوان الماك: ${event.macAddress}</p>
                        
                        <p class="card-text">الوقت: ${event.time}</p>
                    </div>
                </div>
            `;
        }

        // Create a Bootstrap modal
        const modal = `
            <div class="modal" id="eventDetailsModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">تفاصيل الحدث</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            ${modalContent}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        $('body').append(modal);

        // Show the modal
        $('#eventDetailsModal').modal('show');
    }

    function viewUpdateInformation() {
        // Assume you have police member information for demonstration
        const policeMemberInfo = {
            employeeId: '123456',
            policeStationCode: 'PS123',
            name: 'John Doe',
            email: 'john.doe@example.com',
            username: 'john.doe',
        };

        // Create a Bootstrap modal for updating information
        const modalContent = `
            <div class="custom-modal">
                <h5>تحديث المعلومات:</h5>
                <form>
                    <div class="form-group">
                        <label for="employeeId">رقم الموظف:</label>
                        <input type="text" class="form-control" id="employeeId" value="${policeMemberInfo.employeeId}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="policeStationCode">كود محطة الشرطة:</label>
                        <input type="text" class="form-control" id="policeStationCode" value="${policeMemberInfo.policeStationCode}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">الاسم:</label>
                        <input type="text" class="form-control" id="name" value="${policeMemberInfo.name}">
                    </div>
                    <div class="form-group">
                        <label for="email">الايميل:</label>
                        <input type="email" class="form-control" id="email" value="${policeMemberInfo.email}">
                    </div>
                   
                    <button type="button" class="btn btn-primary" onclick="updateInformation()">تحديث</button>
                </form>
            </div>
        `;

        // Create a Bootstrap modal
        const modal = `
            <div class="modal" id="updateInformationModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">تحديث المعلومات</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            ${modalContent}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        $('body').append(modal);

        // Show the modal
        $('#updateInformationModal').modal('show');
    }

    function openResetPasswordModal() {
        // Create a Bootstrap modal for resetting password
        const modalContent = `
            <h5>إعادة ضبط كلمة المرور</h5>
            <form id="resetPasswordForm">
                <div class="form-group">
                    <label for="currentPassword">كلمة السر الحالية:</label>
                    <input type="password" class="form-control" id="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">كلمة السر الجديدة:</label>
                    <input type="password" class="form-control" id="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmNewPassword">تأكيد كلمة السر الجديدة:</label>
                    <input type="password" class="form-control" id="confirmNewPassword" required>
                </div>
                <button type="submit" class="btn btn-primary">إعادة ضبط كلمة المرور</button>
            </form>
        `;

        // Create a Bootstrap modal
        const modal = `
            <div class="modal" id="resetPasswordModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">إعادة ضبط كلمة المرور </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            ${modalContent}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        $('body').append(modal);

        // Show the modal
        $('#resetPasswordModal').modal('show');

        // Handle form submission (reset password logic)
        $('#resetPasswordForm').submit(function (event) {
            event.preventDefault();
            const currentPassword = $('#currentPassword').val();
            const newPassword = $('#newPassword').val();
            const confirmNewPassword = $('#confirmNewPassword').val();

            // Perform the logic to reset the password
            // For demonstration, you can show an alert
            alert('تمت إعادة ضبط كلمة المرور بنجاح!');
            
            // Close the modal
            $('#resetPasswordModal').modal('hide');
        });
    }


    function openLogoutModal() {
        // Create a Bootstrap modal for logout confirmation
        const logoutModal = `
            <div class="modal" id="logoutModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Logout</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>هل انت متأكد انك تريد الخروج?</p>
                            <button type="button" class="btn btn-danger" onclick="logout()">خروج</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        $('body').append(logoutModal);

        // Show the modal
        $('#logoutModal').modal('show');
    }

    function logout() {
        
        alert('تم الخروج!');
        
        window.location.href = 'home.html';
    }

    function updateInformation() {
        alert('تحيث معلومات عضو الشرطة.');
        // Implement your logic to update police member information
        // You can retrieve the updated information from the form fields
        // For simplicity, this is just an alert message
    }
    const reportDropdown = document.getElementsByClassName('reportDropdown');
reportDropdown.addEventListener('change', function() {
  const selectedReportId = reportDropdown.value;
  // استخدم JavaScript لجلب معلومات البلاغ المحدد باستخدام selectedReportId
  // عرض المعلومات في عناصر HTML المناسبة
});
app.get('/reports/:reportId', function(req, res) {
    const reportId = req.params.reportId;
    // استخدم قاعدة البيانات لجلب معلومات البلاغ المحدد بواسطة reportId
    // أرجع المعلومات كاستجابة JSON
  });
  app.put('/reports/:reportId', function(req, res) {
    const reportId = req.params.reportId;
    const updatedReportData = req.body;
    // استخدم قاعدة البيانات لتحديث معلومات البلاغ المحدد بواسطة reportId باستخدام updatedReportData
    // أرجع استجابة JSON للإشارة إلى النجاح أو الفشل
  });
        function openLogoutModal() {
         
        // Create a Bootstrap modal for logout confirmation
        const logoutModal = `
            <div class="modal" id="logoutModal"  style="text-align: right;">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">تسجيل خروج</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>هل تريد تسجيل الخروج؟</p>
                            <button type="button" class="btn btn-danger" onclick="logout()"logout</button> موافق
                        </div>
                    </div>
                </div>
            </div>
        `;
$('body').append(logoutModal);

        // Show the modal
        $('#logoutModal').modal('show');
    }

    function logout() {
        
        alert('Logout successful!');
        
        window.location.href = 'Home.html';
    }
</script>
    </body>
</html>