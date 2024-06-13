<?php
// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "drawersystem";

$macAddressError = '';
$emailError = '';
$idError = '';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign form data to variables
    $name = $_POST['name'];
    $id = $_POST['id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $systemMacAddress = $_POST['system'];
    $address = $_POST['address'];
    $OwnerPassword = $_POST['password'];
    $confirm = $_POST['confirm'];
    $passwordHash = password_hash($OwnerPassword, PASSWORD_DEFAULT);
    // Create a PDO connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if MAC address already exists in the database
        $stmt = $conn->prepare("SELECT * FROM ownert WHERE MACAddress = ?");
        $stmt->bindParam(1, $systemMacAddress); // Bind the parameter directly to the statement
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If MAC address already exists, display feedback to the user
        if ($result) {
            $macAddressError = '<div class="error-message">عنوان MAC مكرر، يرجى التحقق من البيانات المدخلة.</div>';
        } else {
            // Check if Email already exists in the database
            $stmt = $conn->prepare("SELECT * FROM ownert WHERE Email = ?");
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If Email already exists, display feedback to the user
            if ($result) {
                $emailError = '<div class="error-message">البريد الإلكتروني مكرر، يرجى التحقق من البيانات المدخلة.</div>';
            } else {
                // Check if ID already exists in the database
                $stmt = $conn->prepare("SELECT * FROM ownert WHERE OwnerID = ?");
                $stmt->bindParam(1, $id);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // If ID already exists, display feedback to the user
                if ($result) {
                    $idError = '<div class="error-message">رقم الهوية مكرر، يرجى التحقق من البيانات المدخلة.</div>';
                } else {
                    // Proceed with inserting the record into the database
                    // MySQL query to insert the record
                    $stmt = $conn->prepare("INSERT INTO ownert (Name, OwnerID, Email, Address, PhoneNumber, MACAddress, Password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bindParam(1, $name);
                    $stmt->bindParam(2, $id);
                    $stmt->bindParam(3, $email);
                    $stmt->bindParam(4, $address); // Make sure to define $address variable
                    $stmt->bindParam(5, $phone);
                    $stmt->bindParam(6, $systemMacAddress);
                    $stmt->bindParam(7, $passwordHash);
                    $stmt->execute();

                    // Redirect user to login page after successful registration
                    header("Location: logIn.php");
                    exit; // Terminate the script to prevent further execution
                }
            }
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<html  dir="rtl" lang="ar-AR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title> تسجيل جديد </title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'poppins',sans-serif;
    }
    body {
        display: flex;
       min-height: 120vh;
        justify-content: center;
        align-items: center;
        padding: 60px;
         background: linear-gradient(to right, #9c27b0, #8ecdff);
    }
    .white-bar {
        background-color: white;
        height: 60px; 
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 999; 
        display: flex;
        align-items: center;
        padding: 0 20px;
    }
    .logo {
       position: absolute;
       top: 50%; 
       transform: translateY(-50%);
       left: 20px; 
       width: 90px;
       height: 90px;
       z-index: 1000; 
   }
    .white-section {
        background-color: white;
        padding: 100px;
        margin: 100px 0;
        height: calc(780px + 70%);
        width: 800px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding-bottom: 50px;   
    }

    
    .button{
     display:inline-flex;
     grid-template-columns: auto auto; /* ينشئ عمودين بعرض محتوى العناصر */
     justify-content: center; /* يوسط العناصر في المنتصف */
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
        background:#7096d1;
    }
    form .button input:hover{
         background-color:#d0e3ff;
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
       color: red;
        border-radius: 5px;
        font-size: 12px;
    }
    
      #map {
        height: 100px;    
        margin-bottom: 20px;
    }
    .a{
       height: 100vh;
        margin: 0;
        display: flex;
        flex-direction: column-reverse;
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
    .signin{
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
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
            <img src="logo-removebg-preview.png" alt="لوقو" width="90" height="90" class="d-inline-block align-top" style="margin-right: 5px;">
            
            <li class="nav-item">
               <a class="nav-link" href="Home.html">الرئيسية</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="SignUpOwner.php">تسجيل جديد كمالك درج</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="SignUpPolice.php">تسجيل جديد كشرطي</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">تسجيل دخول</a>
            </li>
        </ul>
    </div>
</nav>


   
    <section class="white-section ">
    
       <div class="container" >   
    <div class="title">تسجيل حساب جديد </div>
    <p> انضم الآن لحماية ممتلكاتك</p>
   

    <?php echo $macAddressError; ?>
    <?php echo $emailError; ?>
    <?php echo $idError; ?>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" onsubmit="return validateForm()" id="addressForm" novalidate>
    
    
    <div class="user-details">

            <div class="input-box">
                <span class="details">الاسم الثلاثي </span>
                <input type="text" name="name" id="name" placeholder="  الاسم الثلاثي   " required>
                <div id="name-error" class="error-message"></div> 
            </div>

        <div class="input-box">
            <span class="details">رقم الهوية / الإقامة </span>
            <input type="text" name="id" id="id" placeholder="  رقم الهوية " required>
            <div id="id-error" class="error-message"></div>
        </div>
        
        
        <div class="input-box">
            <span class="details">رقم الجوال<span class="info" onclick="togglePhoneInfo()">i</span></span>
           <div id="phone-info-container" class="info-container" style="display: none;">
    صيغة رقم الجوال (********05) 
</div>

                <input type="tel" name="phone" id="phone" placeholder="  رقم الجوال " required>
            <div id="phone-error" class="error-message"></div> <!-- Error message element for Phone -->
        </div>
        
        
        <div class="input-box">
            <span class="details">البريد الإلكتروني</span>
            <input type="email" name="email" id="email" placeholder="البريد الإلكتروني" required>
            <div id="email-error" class="error-message"></div> <!-- Error message element for Email -->
        </div>

        <div class="input-box">
            <span class="details">الرقم التسلسلي للجهاز (MAC) <span class="info" onclick="toggleMacInfo()">i</span></span>
           <div id="mac-info-container" class="info-container" style="display: none;">
    صيغة الرقم التسلسلي : (XX:XX:XX:XX:XX:XX) 
</div>
            <input type="system" name="system" placeholder="  عنوان MAC  " id="system" required>
            <div id="system-error" class="error-message"></div> 
        </div>
        
       <div class="input-box">
    <span class="details">كلمة المرور</span>
    <input type="password" name="password" placeholder="  ادخل كلمة المرور  " id="password" required>
    <div id="password-strength" class="password-strength"></div> <!-- هنا يجب تحديد الفئة الصحيحة للكلمة المرور -->
    <div id="password-error" class="error-message"></div>
</div>

        

        <div class="input-box">
            <span class="details">تأكيد كلمة المرور </span>
            <input type="password" name="confirm" placeholder="  تأكيد كلمة المرور  " id="confirm-password" required> 
            <div id="confirm-password-error" class="error-message"></div>
        </div>
        
        </div>       
        
        <span class="details">  حدد عنوانك على الخريطة:   </span>
        <div id="map" style="height: 400px;"></div>
        <div id="address"></div>
        <input type="hidden" id="addressInput" name="address" required>
            <div id="addressInput-error" class="error-message"></div> <!-- Error message element for Address -->
     
          <div class="button">
    <button type="button" style="background-color: red;" onclick="resetForm()">مسح</button>
    <button type="submit" name="submit" style="background-color:#7096d1;">تسجيل</button>
</div>

                 <p style="text-align: center;">لديك حساب ؟ <a href="logInP.php">تسجيل الدخول</a></p>
    
    </form>
           </div>
        </section>
        <script> 
            function toggleInfo(){
                var infoContainer = document.querySelector('.info-container');
                infoContainer.style.display =(infoContainer.style.display === 'block' ? 'none' : 'block');
            }
            </script>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
      <script> 
      var map = L.map('map').setView([51.505, -0.09], 13);

// Add OpenStreetMap tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Listen for map click event
map.on('click', function(e) {
    var latlng = e.latlng;
    // Perform reverse geocoding to get address
    // You can use Nominatim or other reverse geocoding services
    fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latlng.lat + '&lon=' + latlng.lng)
        .then(response => response.json())
        .then(data => {
            var address = '(' + latlng.lat + ', ' + latlng.lng + ')';
            document.getElementById('addressInput').value = address;

            // Remove previous marker if exists
            if (typeof marker !== 'undefined') {
                map.removeLayer(marker);
            }

            // Add marker to the map
            marker = L.marker(latlng).addTo(map)
                .bindPopup(address)
                .openPopup();
        })
        .catch(error => {
            console.error('Error:', error);
        });
});


function resetForm() {
        document.getElementById("addressForm").reset();
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
    var idRegex = /^\d{10}$/;
    var phoneRegex = /^05\d{8}$/;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?/~]).{8,}$/;
    var macAddressRegex = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;
    var errorMessage = '';

    switch (field.id) {
        case 'email':
            if (!emailRegex.test(field.value)) {
                errorMessage = 'البريد الإلكتروني يجب أن يكون بتنسيق email@gmail.com.';
            }
            break;
        case 'password':
            if (!passwordRegex.test(field.value)) {
                errorMessage = ' يجب أن تكون كلمة المرور على الأقل 8 أحرف وتحتوي على حرف كبير وحرف صغير ورقم واحد ورمزًا خاصًا على الاقل.';
            }
            break;
        case 'confirm-password': // Corrected ID here
            if (field.value !== document.getElementById('password').value) {
                errorMessage = 'كلمات المرور غير متطابقة.';
            }
            break;
        case 'addressInput':
            if (field.value === '') {
                errorMessage = 'يرجى تحديد عنوانك على الخريطة.';
            }
            break;
        case 'id':
            if (!idRegex.test(field.value)) {
                errorMessage = 'رقم الهوية يجب أن يتكون من 10 أرقام.';
            }
            break;
        case 'phone':
            if (!phoneRegex.test(field.value)) {
                errorMessage = 'رقم الهاتف يجب أن يتكون من 10 أرقام ويبدأ بـ 05';
            }
            break;
        case 'system':
            if (!macAddressRegex.test(field.value)) {
                errorMessage = 'الرجاء إدخال عنوان MAC صالح (XX:XX:XX:XX:XX:XX).';
            }
            break;
        case 'name':
        if (field.value.trim() === '') {
            errorMessage = 'يرجى إدخال الاسم.';
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
 
  <script>
    function toggleInfo() {
        var infoContainers = document.querySelectorAll('.info-container');
        infoContainers.forEach(container => {
            container.style.display = (container.style.display === 'block' ? 'none' : 'block');
        });
    }

    function togglePhoneInfo() {
        var phoneInfoContainer = document.querySelector('#phone-info-container');
        phoneInfoContainer.style.display = (phoneInfoContainer.style.display === 'block' ? 'none' : 'block');
    }

    function toggleMacInfo() {
        var macInfoContainer = document.querySelector('#mac-info-container');
        macInfoContainer.style.display = (macInfoContainer.style.display === 'block' ? 'none' : 'block');
    }
</script>


    
    
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
        strength +=1; // Setting strength to 5 if there's at least one special character
    }      
    if (password.length >= 8) {
        strength += 1;
    }
    

    let passwordStrengthElement = document.getElementById("password-strength");

    switch (strength) {
        case 0:
            passwordStrengthElement.textContent = "ضعيفة";
            passwordStrengthElement.className = "weak";
        case 1:
            passwordStrengthElement.textContent = "ضعيفة";
            passwordStrengthElement.className = "weak";
            break;
        case 2:
            passwordStrengthElement.textContent = "متوسطة";
            passwordStrengthElement.className = "medium";
            break;
        case 3:
            passwordStrengthElement.textContent = "متوسطة";
            passwordStrengthElement.className = "medium";
            break;
        case 4:
            passwordStrengthElement.textContent = "قوية";
            passwordStrengthElement.className = "strong";
            break;
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

</body>
</html>