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

    // Retrieve the current username from the database
    $userUsername = $_SESSION["userUsername"];
    $stmt = $conn->prepare("SELECT * FROM User WHERE userUsername = ?");
    $stmt->bind_param("s", $userUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userRegistrationId = $row["userRegistrationId"];
    } else {
        echo "User not found.";
        exit;
    }

    $stmt = $conn->prepare("SELECT userUsername FROM User WHERE userRegistrationId = ?");
    $stmt->bind_param("s", $userRegistrationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $currentUsername = $row['userUsername'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Change Username
        if (isset($_POST["changeUsername"])) {
            $newUsername = $_POST['newUsername'];
            $confirmNewUsername = $_POST['confirmNewUsername'];

            // Check if the new username is available
            $stmt = $conn->prepare("SELECT * FROM User WHERE userUsername = ?");
            $stmt->bind_param("s", $newUsername);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                echo "The username is already taken.";
            } elseif ($newUsername !== $confirmNewUsername) {
                echo "The new username and confirmation do not match.";
            } else {
                // Update the username in the database
                $stmt = $conn->prepare("UPDATE User SET userUsername = ? WHERE userRegistrationId = ?");
                $stmt->bind_param("ss", $newUsername, $userRegistrationId);

                if ($stmt->execute()) {
                    // Username updated successfully
                    $_SESSION['userUsername'] = $newUsername;
                    echo "Username updated.";
                } else {
                    echo "Error updating username: " . $stmt->error;
                }

                $stmt->close();
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
    <title>Settings</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div>Settings</div>
    <div>
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
            <h2>Change Username</h2>
            Current Username: <?php echo htmlspecialchars($userUsername); ?>
            <br>
            <label for="newUsername">New Username:</label>
            <input type="text" name="newUsername" id="newUsername" required>
            <br>
            <label for="confirmNewUsername">Confirm New Username:</label>
            <input type="text" name="confirmNewUsername" id="confirmNewUsername" required>
            <br>
            <input type="submit" value="Change" name="changeUsername">
        </form>
        <br>
        <a href="settings.php">Return to Settings</a>
    </div>
</body>
</html>
