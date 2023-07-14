<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
        header("Location: login.php"); // Redirect to the login page if not logged in
        exit;
    }

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the selected option
        $option = $_POST["option"];

        // Redirect to the respective PHP file based on the selected option
        if ($option == "profile-picture") {
            header("Location: upload_profile_picture.php");
            exit();
        } elseif ($option == "change-username") {
            header("Location: change_username.php");
            exit();
        } elseif ($option == "change-password") {
            header("Location: change_password.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
</head>
<body>
    <h1>Settings</h1>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="option">Select an option:</label>
        <select name="option" id="option">
            <option value="profile-picture">Upload profile picture</option>
            <option value="change-username">Change username</option>
            <option value="change-password">Change password</option>
        </select>
        <br><br>
        <button type="submit">Go</button>
    </form>
    <br><a href="home.php">Return to Home</a> 
</body>
</html>
