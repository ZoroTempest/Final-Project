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

    // Check if the user is logged in
    if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
        header("Location: login.php"); // Redirect to the login page if not logged in
        exit;
    }

    $userUsername = $_SESSION["userUsername"];

    $stmt = $conn->prepare("SELECT * FROM User WHERE userUsername = ?");
    $stmt->bind_param("s", $userUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userRegistrationId = $row["userRegistrationId"];
        $userFirstName = $row["userFirstName"];
        $userMiddleName = $row["userMiddleName"];
        $userLastName = $row["userLastName"];
        $userEmailAddress = $row["userEmailAddress"];
       
        $registrationDate = $row["registrationDate"];
        $expirationDate = $row["expirationDate"];
        $userStatus = $row["userStatus"];
        $userPhoto = $row["userPhoto"];
    } else {
        echo "User not found.";
        exit;
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
        <title>User Information</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="box3.css">
        <link rel="stylesheet" href="textinsidebox.css">
</head>
<body>
    <!-- Your top navigation bar -->
    <div class="topnav">
        <a href="home.php">Home</a>
        <div class="right-options">
            <a class="active" href="userinfo.php">User Profile</a>
            <a href="login.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="profile">
            <!-- Profile information -->
        </div>
        <br>
        <div class="content2">
            <div class="navbar">
                <a href="#" onclick="loadAllInfo()">Show All</a>
                <a href="#" onclick="loadAccountInfo()">Account</a>
                <a href="#" onclick="loadPersonalInfo()">Personal</a>
                <a href="#" onclick="loadAddressInfo()">Address</a>
            </div>
            <!-- Content based on the selected navigation link -->
            <div id="infoContent">
                <?php
                if (!isset($_GET['info']) || $_GET['info'] === 'all') {
                } elseif ($_GET['info'] === 'account') {
                } elseif ($_GET['info'] === 'personal') {
                } elseif ($_GET['info'] === 'address') {
                }
                ?>
            </div>
        </div>
        <br>
        <div class="box3">
            <a href="settings.php">Settings</a>
        </div>
    </div>

    <!-- Your script to handle navigation and show the information -->
    <script>
        function loadAllInfo() {
            document.getElementById("infoContent").innerHTML = `
                <div class="text">
                    <normal style="font-size: 30px; color: black;">User ID:</normal>
                <div class="text-box">
                    <?php echo htmlspecialchars($userRegistrationId); ?>
                </div>
                 </div>
                <div>
                <normal style = "font-size:30px; color:black">Username:</normal>
                <div class="text-box">
                    <?php echo htmlspecialchars($userUsername); ?>
                </div>
                </div>
                <div>
                    <normal style = "font-size:30px; color:black">Email:</normal>
                    <div class="text-box">
                        <?php echo htmlspecialchars($userEmailAddress); ?>
                </div>
                </div>
                <div>
                    <normal style = "font-size:30px; color:black">Full Address:</normal>
                <div class="text-box">
                    <?php echo htmlspecialchars($row['userHouseNumber']) . " " . htmlspecialchars($row['userStreet']) . " " . htmlspecialchars($row['userBarangay']) . " " . htmlspecialchars($row['userCity']) . " " . htmlspecialchars($row['userProvince']) . " " . htmlspecialchars($row['userZipCode']); ?>
                </div>
                </div>
                <div>
                    <normal style = "font-size:30px; color:black">Registration Date:</normal>
                <div class="text-box">
                    <?php echo htmlspecialchars($registrationDate); ?>
                </div>
                </div>
                <div>
                    <normal style = "font-size:30px; color:black">Expiration Date:</normal>
                <div class="text-box">
                    <?php echo htmlspecialchars($expirationDate); ?>
                </div>
                </div>
                <div>
                    <normal style = "font-size:30px; color:black">Status:</normal>
                    <div class="text-box">
                        <?php echo htmlspecialchars($userStatus); ?>
                </div>
                </div>
            `;
        }

        function loadAccountInfo() {
            document.getElementById("infoContent").innerHTML = `
                <div>

                </div>
            `;
        }

        function loadPersonalInfo() {
            document.getElementById("infoContent").innerHTML = `
                <div>

                </div>
            `;
        }

        function loadAddressInfo() {
            document.getElementById("infoContent").innerHTML = `
                <div>

                </div>
            `;
        }

        window.onload = function() {
            loadAllInfo();
        };
    </script>
</body>
</html>
