<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "UserInformation";
$conn = new mysqli($servername, $username, $password, $databasename);

if (mysqli_connect_error()) {
    die("Connection Failed: " . mysqli_connect_error());
}

$userUsername = $_SESSION["userUsername"];

$stmt = $conn->prepare("SELECT * FROM User WHERE userUsername = ?");
$stmt->bind_param("s", $userUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userFirstName = $row["userFirstName"];
    $userMiddleName = $row["userMiddleName"];
    $userLastName = $row["userLastName"];
    $userPhoto = $row["userPhoto"];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="topnav">
        <a class="active" href="#home">Home</a>
        <div class="right-options">
            <a href="userinfo.php">User Profile</a>
            <a href="login.php">Logout</a>
        </div>
    </div>
    <br>
    <div class="content">
        <div class="container">     
            <h1>Land Transportation Management System</h1>
            <div class="profile">
                <?php if (!empty($userPhoto)): ?>
                    <img src="<?php echo htmlspecialchars($userPhoto); ?>" alt="User Photo">
                <?php endif; ?>   
                <h2>Welcome, <?php echo($userUsername); ?>!</h2>
                <h3>To your one-stop destination for managing your personal information and account settings</h3>
            </div>
        </div>
        <br>
    </div>
</body>
</html>
