<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "UserInformation";

    $conn = new mysqli($servername, $username, $password, $database);

    if (mysqli_connect_error()){
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the current username from the session
    $userUsername = $_SESSION["userUsername"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Change Password
        if (isset($_POST["changePassword"])) {
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            // Retrieve the current password from the database
            $stmt = $conn->prepare("SELECT userPassword FROM User WHERE userUsername = ?");
            $stmt->bind_param("s", $userUsername);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $storedPassword = $row['userPassword'];

            // Check if the current password matches the stored password
            if ($currentPassword !== $storedPassword) {
                echo "Incorrect current password.";
            } else {
                // Check if the new password and confirm password match
                if ($newPassword !== $confirmPassword) {
                    echo "New password and confirm password do not match.";
                } else {
                    // Update the password in the database
                    $stmt = $conn->prepare("UPDATE User SET userPassword = ? WHERE userUsername = ?");
                    $stmt->bind_param("ss", $newPassword, $userUsername);

                    if ($stmt->execute()) {
                        echo "Password changed successfully.";
                    } else {
                        echo "Error changing password: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }
        }
    }

    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div>Change Password</div>
    <div>
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
            <h2>Change Password</h2>
            <label for="currentPassword">Current Password:</label>
            <input type="password" name="currentPassword" id="currentPassword" required>
            <br>
            <label for="newPassword">New Password:</label>
            <input type="password" name="newPassword" id="newPassword" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$">
            <br>
            <label for="confirmPassword">Confirm New Password:</label>
            <input type="password" name="confirmPassword" id="confirmPassword" required pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$">
            <br>
            <input type="submit" value="Change" name="changePassword">
        </form>
        <br>
        <a href="settings.php">Return to Settings</a>
    </div>
</body>
</html>
