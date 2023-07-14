<?php
    session_start();
    $_SESSION["login"] = false;

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

    if (isset($_POST["login"])) {
        function cleanInput($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $servername = "localhost";
        $username = "root";
        $password = "";
        $databasename = "UserInformation";
        $userUsername = cleanInput($_POST['userUsername']);
        $userPassword = cleanInput($_POST['userPassword']);

        $conn = new mysqli($servername, $username, $password, $databasename);
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM User WHERE userUsername = ?");
        $stmt->bind_param("s", $userUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if ($userPassword === $row["userPassword"]) {
                $_SESSION["login"] = true;
                $_SESSION["userUsername"] = $row["userUsername"];
                $_SESSION["userPassword"] = $row["userPassword"];

                if (isset($_POST['remember'])) {
                    setcookie('userUsername', $_POST['userUsername'], time() + 3600);
                    setcookie('userPassword', $_POST['userPassword'], time() + 3600);
                } else {
                    setcookie('userUsername', '', time() - 3600);
                    setcookie('userPassword', '', time() - 3600);
                }

                header("location: home.php");
            } else {
                echo "Incorrect username and/or password!";
            }
        } else {
            echo "No account found! Please make sure you registered";
        }

        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div>LOGIN</div>
    <div>
        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
            <div>
                <label for="userUsername"><br> USERNAME </label><br>
                <input type="text" name="userUsername" placeholder="Enter Username" required value="<?php echo isset($_COOKIE['userUsername']) ? $_COOKIE['userUsername'] : ''; ?>"><br>
            </div>
            <div>
                <label for="userPassword"><br> PASSWORD </label><br>
                <input type="password" id="userPassword" name="userPassword" placeholder="Enter Password" required value="<?php echo isset($_COOKIE['userPassword']) ? $_COOKIE['userPassword'] : ''; ?>">
            </div>
            <div>
                <br><input type="checkbox" id="remember" name="remember" value="remember">
                <label for="remember">Remember Me</label><br>
                <button name="login" value="login"> LOGIN </button><br>
            </div>
        </form>
        <div>
            No account yet? <a href="register.php">CREATE AN ACCOUNT</a><br><br>
        </div>
    </div>
</body>
</html>
