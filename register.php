<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "UserInformation";

$conn = new mysqli($servername, $username, $password);

if (mysqli_connect_error()){
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";

if ($conn->query($sql) === FALSE){
    echo "Error creating database: " . $conn->error;
}

$conn->close();

$conn = new mysqli($servername, $username, $password, $database);

$sql = "CREATE TABLE IF NOT EXISTS User (
    userId INT(10) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    userRegistrationId VARCHAR(10) NOT NULL,
    userFirstName VARCHAR(100) NOT NULL,
    userMiddleName VARCHAR(100) NOT NULL,
    userLastName VARCHAR(100) NOT NULL,
    userSuffix VARCHAR(100),
    userHouseNumber VARCHAR(100),
    userStreet VARCHAR(100),
    userBarangay VARCHAR(100),
    userCity VARCHAR(100),
    userProvince VARCHAR(100),
    userZipCode VARCHAR(10),
    userUsername VARCHAR(100) NOT NULL,
    userEmailAddress VARCHAR(100) NOT NULL,
    userContactNumber VARCHAR(11) NOT NULL,
    userPassword VARCHAR(255) NOT NULL,
    userStatus VARCHAR(100) NOT NULL,
    registrationDate DATE NOT NULL,
    expirationDate DATE NOT NULL,
    userPhoto VARCHAR(255),
    userValidId VARCHAR(255) NOT NULL
)";    

if ($conn->query($sql) === FALSE){
    echo "Error creating table: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div>REGISTER</div>
    <div>
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
            <div>
                <label for="userFirstName"><br> FIRST NAME </label><br>
                <input type="text" name="userFirstName" placeholder="Enter First Name" required><br>
            </div>
            <div>
                <label for="userMiddleName"> MIDDLE NAME </label><br>
                <input type="text" name="userMiddleName" placeholder="Enter Middle Name" required><br>
            </div>
            <div>
                <label for="userLastName"> LAST NAME </label><br>
                <input type="text" name="userLastName" placeholder="Enter Last Name" required><br>
            </div>
            <div>
                <label for="userUsername"> USERNAME </label><br>
                <input type="text" name="userUsername" placeholder="Enter Username" required><br>
            </div>
            <div>
                <label for="userSuffix"> SUFFIX </label><br>
                <select id="userSuffix" name="userSuffix" onchange="handleSuffixChange(this.value)">
                    <option value=""> </option>
                    <option value="Jr.">Jr</option>
                    <option value="Sr.">Sr</option>
                    <option value="III">I</option>
                    <option value="IV">II</option>
                    <option value="V">III</option>
                    <option value="others">Others</option>
                </select>
            </div>
            <div>
                <label for="userHouseNumber"> HOUSE NUMBER / BLOCK AND LOT NUMBER </label><br>
                <input type="text" name="userHouseNumber" placeholder="Enter House Number / Block and Lot Number" required>
            </div>
            <div>
                <label for="userStreet"> STREET / VILLAGE / SUBDIVISION </label><br>
                <input type="text" name="userStreet" placeholder="Enter Street / Village / Subdivision" required>
            </div>
            <div>
                <label for="userBarangay"> BARANGAY </label><br>
                <input type="text" name="userBarangay" placeholder="Enter Barangay" required>
            </div>
            <div>
                <label for="userCity"> CITY / MUNICIPALITY </label><br>
                <input type="text" name="userCity" placeholder="Enter City / Municipality" required>
            </div>
            <div>
                <label for="userProvince"> PROVINCE </label><br>
                <input type="text" name="userProvince" placeholder="Enter Province" required>
            </div>
            <div>
                <label for="userZipCode"> ZIP CODE </label><br>
                <input type="text" name="userZipCode" placeholder="Enter Zip Code" required>
            </div>
            <div>
                <label for="userEmailAddress"> EMAIL ADDRESS </label><br>
                <input type="email" id="userEmailAddress" name="userEmailAddress" placeholder="Enter Email Address" required><br>
            </div>
            <div>
                <label for="userContactNumber"> CONTACT NUMBER </label><br>
                <input type="text" id="userContactNumber" name="userContactNumber" placeholder="09xxxxxxxxx" pattern="09\d{9}" inputmode="numeric" required>
            </div>
            <div>
                <label for="userPassword"> PASSWORD </label><br>
                <input type="password" id="userPassword" name="userPassword" placeholder="Enter Password" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$"><br>
            </div>
            <div>
                <label for="confirmPassword"> CONFIRM PASSWORD </label><br>
                <input type="password" name="confirmPassword" placeholder="Enter Password Again" required><br>
            </div>
            <div>
                <label for="userPhoto"> UPLOAD USER PHOTO </label><br>
                <input type="file" name="userPhoto" accept="image/*"><br>
            </div>
            <div>
                <label for="userValidId"><br> UPLOAD VALID ID </label><br>
                <input type="file" name="userValidId" accept="image/*"><br>
            </div>
            <br><button name="save" value="SAVE"> REGISTER </button>
        </form>

        <br><div>Already have an account? <a href="login.php">LOGIN</a><br><br></div>
    </div>

    <?php
    $allowedFormats = array('jpg', 'jpeg', 'png', 'gif');
    if (isset($_POST["save"])){
        function cleanInput($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            return $data;
        }

        function getUserStatus($registrationDate, $expirationDate) {
            $currentDate = date('Y-m-d');
            if ($currentDate >= $registrationDate && $currentDate <= $expirationDate) {
                return "Active";
            } else {
                return "Expired";
            }
        }

        $userFirstName = cleanInput($_POST['userFirstName']);
        $userMiddleName = cleanInput($_POST['userMiddleName']);
        $userLastName = cleanInput($_POST['userLastName']);
        $userSuffix = cleanInput($_POST['userSuffix']);
        $userHouseNumber = cleanInput($_POST['userHouseNumber']);
        $userStreet = cleanInput($_POST['userStreet']);
        $userBarangay = cleanInput($_POST['userBarangay']);
        $userCity = cleanInput($_POST['userCity']);
        $userProvince = cleanInput($_POST['userProvince']);
        $userZipCode = cleanInput($_POST['userZipCode']);
        $userUsername = cleanInput($_POST['userUsername']);
        $userEmailAddress = cleanInput($_POST['userEmailAddress']);
        $userContactNumber = cleanInput($_POST['userContactNumber']);
        $userPassword = cleanInput($_POST['userPassword']);
        $confirmPassword = cleanInput($_POST['confirmPassword']);
        $flag = 0;

        // Calculate expiration date (five years from registration date)
        $registrationDate = date('Y-m-d');
        $expirationDate = date('Y-m-d', strtotime('+5 years', strtotime($registrationDate)));

        // Determine user status based on registration and expiration dates
        $userStatus = getUserStatus($registrationDate, $expirationDate);

        // Empty Input Validation
        if (empty($userFirstName) || empty($userLastName) || empty($userUsername) || empty($userEmailAddress) || empty($userContactNumber) || empty($userPassword) || empty($userStatus)){
            echo "Please fill in all required fields.";
            $flag = 1;
        }

        // Email Address Validation
        if (!filter_var($userEmailAddress, FILTER_VALIDATE_EMAIL)){
            echo "Invalid email address.";
            $flag = 1;
        }

        // Unique Email Validation
        $stmt = $conn->prepare("SELECT * FROM User WHERE userEmailAddress = ?");
        $stmt->bind_param("s", $userEmailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0){
            echo "The email address is already taken.";
            $flag = 1;
        }

        // Unique Username Validation
        $stmt = $conn->prepare("SELECT * FROM User WHERE userUsername = ?");
        $stmt->bind_param("s", $userUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0){
            echo "The username is already taken.";
            $flag = 1;
        }

        // Password Validation
        if ($userPassword !== $confirmPassword){
            echo "Passwords do not match.";
            $flag = 1;
        }

        // Generate userRegistrationId
        $currentYear = date('Y');
        $registrationYear = substr($registrationDate, 0, 4);
        $userRegistrationId = '';

        // Check if registration year is the current year
        if ($registrationYear == $currentYear) {
            // Get the last userRegistrationId for the current year
            $stmt = $conn->prepare("SELECT MAX(userRegistrationId) FROM User WHERE registrationDate LIKE ?");
            $likePattern = $registrationYear . '%';
            $stmt->bind_param("s", $likePattern);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $lastUserRegistrationId = $row['MAX(userRegistrationId)'];

            // Increment the last userRegistrationId
            if ($lastUserRegistrationId) {
                $lastIdNumber = substr($lastUserRegistrationId, 4);
                $nextIdNumber = intval($lastIdNumber) + 1;
                $userRegistrationId = $registrationYear . str_pad($nextIdNumber, 6, '0', STR_PAD_LEFT);
            }
        }

        // Set the default userRegistrationId if it's not generated
        if (empty($userRegistrationId)) {
            $userRegistrationId = $currentYear . str_pad(1, 6, '0', STR_PAD_LEFT);
        }

        // Upload photo
        if ($flag == 0){
            if (!empty($_FILES["userValidId"]["tmp_name"])) {
                // File handling for userValidId
                $targetDirectory = "./uploads2/";
                $targetFile1 = $targetDirectory . basename($_FILES["userValidId"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile1, PATHINFO_EXTENSION));

                // Check if the uploaded file has a valid format
                if (!in_array($imageFileType, $allowedFormats)) {
                    echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Create the "uploads2" directory if it doesn't exist
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                // Move the uploaded file to the desired directory
                if (!($uploadOk && move_uploaded_file($_FILES["userValidId"]["tmp_name"], $targetFile1))) {
                    // Error moving the file
                    echo "Sorry, there was an error moving the uploaded file.";
                    $targetFile1 = null; // Set targetFile to null if file upload failed
                }
            } else {
                echo "Please upload a valid ID.";
                $flag = 1;
            }
            
            // Check if a file was selected for userPhoto
            if (!empty($_FILES["userPhoto"]["tmp_name"])) {
                // File handling for userPhoto
                $targetDirectory = "./uploads2/";
                $targetFile2 = $targetDirectory . basename($_FILES["userPhoto"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile2, PATHINFO_EXTENSION));

                // Check if the uploaded file has a valid format
                if (!in_array($imageFileType, $allowedFormats)) {
                    echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Create the "uploads2" directory if it doesn't exist
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0777, true);
                }

                // Move the uploaded file to the desired directory
                if ($uploadOk && move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $targetFile2)) {
                    // File moved successfully
                    echo "File uploaded successfully.";
                } else {
                    // Error moving the file
                    echo "Sorry, there was an error moving the uploaded file.";
                    $targetFile2 = null; // Set targetFile to null if file upload failed
                }
            } else {
                $targetFile2 = null; // Set targetFile to null if no file was selected
            }

            // Use default photo if no photo was uploaded
            if (empty($targetFile2)) {
                $defaultPhotoFile = "userPIC.jpg";
                $defaultPhotoPath = $targetDirectory . $defaultPhotoFile;

                if (!file_exists($defaultPhotoPath)) {
                    copy("path/to/default/UserPIC.jpg", $defaultPhotoPath);
                }
                
                $targetFile2 = $defaultPhotoPath;
            }

            
            // Insert user record into the database
            if ($userPassword === $confirmPassword && $flag == 0) {
                $stmt = $conn->prepare("INSERT INTO User (userRegistrationId, userFirstName, userMiddleName, userLastName, userSuffix, userUsername, userEmailAddress, userPassword, userStatus, userValidId, userPhoto, registrationDate, expirationDate, userContactNumber, userHouseNumber, userStreet, userBarangay, userCity, userProvince, userZipCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssssssssssssss", $userRegistrationId, $userFirstName, $userMiddleName, $userLastName, $userSuffix, $userUsername, $userEmailAddress, $userPassword, $userStatus, $targetFile1, $targetFile2, $registrationDate, $expirationDate, $userContactNumber, $userHouseNumber, $userStreet, $userBarangay, $userCity, $userProvince, $userZipCode);

                if ($stmt->execute()) {
                    // Registration is successful
                    echo "Registration successful!";
                } else {
                    echo "Error: " . $stmt->error . "";
                }

                $stmt->close();
            } else if ($flag == 0) {
                echo "Passwords do not match. Try again.";
            }

            $conn->close();
        }
    }
    ?>
</body>
</html>
