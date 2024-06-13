<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drawersystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $id = $email = $policePassword = $confirmPassword = "";
$errorMsg = ""; // Initialize error message variable

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $policePassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Check if email exists in police table
    $checkEmailQuery = "SELECT * FROM police WHERE Email = ?";
    $checkStmt = $conn->prepare($checkEmailQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Email already exists in police table, display error message
        $errorMsg = '<div class="error-message"> البريد الإلكتروني المدخل موجود بالفعل.</div>';
    } else {
        // Email does not exist, proceed with inserting the data
        // Insert data into police table
        $sql = "SELECT Name, EmployeeID, Code, Position FROM policemember WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Email exists in policemember table, retrieve Name, ID, and Code
            $row = $result->fetch_assoc();
            $name = $row['Name'];
            $id = $row['EmployeeID'];
            $code = $row['Code'];
            // Check if 'Position' exists in the $row array before attempting to access it
            $position = isset($row['Position']) ? $row['Position'] : '';

            // Hash the password
            $passwordHash = password_hash($policePassword, PASSWORD_DEFAULT);

            // Insert data into police table
            $insertStmt = $conn->prepare("INSERT INTO police (Name, EmployeeID, Email, Code, Password, Position) VALUES (?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("ssssss", $name, $id, $email, $code, $passwordHash, $position);

            // Execute the insert statement
            if ($insertStmt->execute()) {
                header("Location: logIn.php");
                exit;
            } else {
                echo "Error: " . $insertStmt->error;
            }

            // Close the insert statement
            $insertStmt->close();
        } else {
            // Email does not exist in policemember table, set error message
            $errorMsg = '<div class="error-message"> البريد الالكتروني المدخل غير موجود في قاعدة بيانات الشرطة .</div>';
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>



<html  dir="rtl" lang="ar-AR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>تسجيل جديد </title>
    
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'poppins',sans-serif;
        }
        body {
            display: flex;
            height: 120vh;
            justify-content: center;
            align-items: center;
            padding: 10px;
             background: linear-gradient(to right, #9c27b0, #8ecdff);
        }
       .home-link{
            position: fixed;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: white;
            font-size: 20px;
            font-family: 'Poppins', sans-serif;
            z-index: 999;
        }
        .container{
           max-width: 700px;
            width: 100%;
            background: #fff;
            padding: 25px 30px;
            border-radius: 5px;
            margin-top: 30px;
        }
        
        .container .title{
            font-size: 25px;
            font-weight: 500;
            position: relative;
        }
        .container .title:before{
            content: '';
            position: absolute;
            right: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background: linear-gradient(to right, #9c27b0, #8ecdff);     
        }
        .container form .user-details{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px 0 12px 0;
        }
        form .user-details .input-box{
            margin-bottom: 15px;
            width: calc(100% / 2 - 20px);
        }
         .user-details .input-box .details{
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .user-details .input-box input{
            height: 45px;
            width: 100%;
            outline: none;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding-left: 15px;
            font-size: 16px;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }
         .user-details .input-box input:focus,
        .user-details .input-box input:valid{
         border-color:  #9c27b0;
        }
        
.button button {
        width: 150px; /* يمكنك تعديل هذا الحجم حسب احتياجاتك */
        height: 45px;
        margin: 0 10px; /* يمكنك ضبط التباعد بين الأزرار */
    }
.button input[type="button"][value="مسح"] {
background-color: red; 
height: 45px; 
width: 250px;
}
.button input[type="submit"][value="تسجيل"] {
height: 45px; 
width: 270px;
    }
        .button input[type="submit"] {
height: 45px;
width: 150px;
 margin: 0 10px;        
}
.delete-button {
    background-color: red;
    color: white; 
    border: none;
    padding: 10px 20px; 
    border-radius: 5px; 
}
         form .button{
        height: 45px;
        margin: 45px 0;    
        }
        form .button input{
            height: 100%;
            width: 100%;
            outline: none;
            color:white;
            border: none;
            font-size: 18px;
            font-weight: 500;
            border-radius: 5px;
            letter-spacing: 1px;
            background: #9c27b0;
        }
        form .button input:hover{
             background: #9c27b0;
        }
        @media (max-width: 584px){
              .container{
            max-width: 100%;
            }
             form .user-details .input-box{
            margin-bottom: 15px;
            width: 100%;
        }
           .container form .user-details{
               max-height: 300px;
               overflow-y: scroll;
            }
            .user-details::webkit-scrollbar{
                width: 5;
            }
        }
        .logo{
            float: right;
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: auto;
        }
        .user-details .input-box .details .info{
            margin-left: 5px;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            background-color: #fff;
            border-radius: 50%;
            border: 1px solid #000;
        }
        .info-container{
            display: none;
            margin-top: 5px;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            font-size: 12px;
            overflow-y: scroll;
        }
        .user-details{
            display: grid;
            grid-template-columns: repeat(2,1fr);
            grid-gap: 10px;
        }
          #password-strength {
            font-size: 14px;
            margin-top: 5px;
        }
        .weak {
            color: red;
        }

.medium {
    color: orange;
        }

.strong {
    color: green;
        }

.very-strong {
     color: blue;
}
   
         .home-link{
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 999;
            text-decoration: none;
            color: #708090;
            font-size: 20px;
             transition: color 0.3s ease;
        }
        .home-link:hover {
           color: black; 
       }
       .error-message {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
         .navbar {
       position: fixed;
       top: 0;
       width: 100%;
       background-color:white; 
       z-index: 999;
       height: 80px;
        }
.nav-item.active a {
    border-bottom: 2px solid cornflowerblue; 
}

    .navbar-nav {
        display: flex;
        align-items: center; /* لمحاذاة العناصر عمودياً في الوسط */
        list-style: none; /* إزالة نقاط السهم السوداء من القائمة نفسها */
    }

    .navbar-nav .nav-item {
        margin-left: 10px; /* تباعد بسيط بين العناصر */
    }

    .navbar-nav .nav-item .nav-link {
        padding: .5rem 1rem; /* تعديل التباعد الداخلي للروابط */
        color: black; /* تغيير لون النص إلى الأسود */
        text-decoration: none; /* إزالة التأثير الافتراضي لتحت خط الروابط */
        display: inline-block; /* عرض الروابط كعناصر مستقلة بدون تأثيرات إضافية */
    }

    .navbar-nav .nav-item .nav-link:hover {
        color: purple; /* تغيير لون النص عند التحويم فوقه إلى اللون البنفسجي */
    }
    </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <img src="logo.html.png" alt="لوقو" width="90" height="90" class="d-inline-block align-top" style="margin-right: 5px;">
            
            <li class="nav-item">
               <a class="nav-link" href="Home.html">الرئيسية</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="SignUpOwner.php">تسجيل جديد كمالك درج</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="SignUpPolice.php">تسجيل جديد كشرطي</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">تسجيل دخول</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="title">تسجيل حساب جديد</div>
    <?php if (!empty($errorMsg)) : ?>
        <p><?php echo $errorMsg; ?></p>
    <?php endif; ?>
    <form id="signupForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()" novalidate>

    <div class="user-details">
        
         <div class="input-box">
        <span class="details">البريد الإلكتروني</span>
        <input type="text" id="email" name="email" placeholder="example@example.com" required>
        <div id="email-error" class="error-message"></div>
        </div>
           <div class="input-box">
        <span class="details">  كلمة المرور </span>
        <input type="password" id="password" name="password" placeholder="   كلمة المرور  " required>
          <span id="password-strength"></span>  
          <div id="password-error" class="error-message"></div>    
        </div>
        
           <div class="input-box">
        <span class="details"> تأكيد كلمة المرور </span>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="  تأكيد كلمة المرور  " required>
        <div id="confirm-password-error" class="error-message"></div> 
        </div>
        </div>
          <div class="button">
    <button type="button" style="background-color: red;" onclick="resetForm()">مسح</button>
    <button type="submit" name="submit" style="background-color:#7096d1;">تسجيل</button>
</div>
             <p style="text-align: center;">لديك حساب ؟ <a href="logIn.php">تسجيل الدخول</a></p>
    
    </form>
    </div>

<script>

function checkPasswordStrength(password) {
    let strength = 0;

    if (password.match(/[a-z]+/)) {
        strength += 1;
    }
    if (password.match(/[A-Z]+/)) {
        strength += 1;
    }
    if (password.match(/[0-9]+/)) {
        strength += 1;
    }
    if (password.match(/[!@#$%^&*()_+{}\[\]:;<>,.?/~]+/)) {
        strength += 1; // الفحص عن حرف خاص
    }     
    if (password.length >= 8) {
        strength += 1;
    }

    let passwordStrengthElement = document.getElementById("password-strength");

    switch (strength) {
        case 0:
        case 1:
            passwordStrengthElement.textContent = "ضعيفة";
            passwordStrengthElement.className = "weak";
            break;
        case 2:
            passwordStrengthElement.textContent = "متوسطة";
            passwordStrengthElement.className = "medium";
            break;
        case 3:
            passwordStrengthElement.textContent = "قوية";
            passwordStrengthElement.className = "strong";
            break;
        case 4:
        case 5:
            passwordStrengthElement.textContent = "قوية جدًا";
            passwordStrengthElement.className = "very-strong";
            break;
        default:
            break;
    }
}
document.getElementById("password").addEventListener("input", function() {
    checkPasswordStrength(this.value);
});


    </script>
    
    <script>
        function resetForm() {
        document.getElementById("signupForm").reset();
    }

    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            localStorage.setItem(this.id, this.value);
        });
    });

    // Populate form fields with stored data on page load
    window.addEventListener('load', function() {
        document.querySelectorAll('input').forEach(input => {
            input.value = localStorage.getItem(input.id) || '';
        });
    });
        function validateField(field) {
        var emailRegex = /^[^\s@]+@moi\.gov\.sa$/;
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?/~]).{8,}$/;
        var errorMessage = '';
    
        switch (field.id) {
            case 'email':
                if (!emailRegex.test(field.value)) {
                    errorMessage = 'الايميل غير صالح';
                }
                break;
            case 'password':
                if (!passwordRegex.test(field.value)) {
                    errorMessage = ' يجب أن تكون كلمة المرور على الأقل 8 أحرف وتحتوي على حرف كبير وحرف صغير ورقم واحد ورمزًا خاصًا على الاقل.';
                }
                break;
            case 'confirm-password': 
                if (field.value !== document.getElementById('password').value) {
                    errorMessage = 'كلمات المرور غير متطابقة.';
                }
                break;
            
        }
    
        var errorElement = document.getElementById(field.id + '-error');
        if (errorMessage) {
            errorElement.textContent = errorMessage;
            field.classList.add('invalid');
        } else {
            errorElement.textContent = ''; // Clear error message when the input is valid
            field.classList.remove('invalid');
        }
    }
    
    
    
    
    function validateForm() {
        var formIsValid = true;
        document.querySelectorAll('input').forEach(input => {
            validateField(input);
            if (input.classList.contains('invalid')) {
                formIsValid = false;
            }
        });
        return formIsValid;
    }
    </script>
</body>
</html>