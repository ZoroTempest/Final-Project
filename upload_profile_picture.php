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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if a file was selected
        if (!empty($_FILES["userPhoto"]["tmp_name"])) {
            $allowedFormats = array('jpg', 'jpeg', 'png', 'gif');
            $targetDirectory = "./uploads2/";
            $targetFile = $targetDirectory . basename($_FILES["userPhoto"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

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
            if ($uploadOk && move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $targetFile)) {

                // Update user's profile photo in the database
                $userUsername = $_SESSION["userUsername"];
                $userPhoto = $targetFile;

                $stmt = $conn->prepare("UPDATE User SET userPhoto = ? WHERE userUsername = ?");
                $stmt->bind_param("ss", $userPhoto, $userUsername);

                if ($stmt->execute()) {
                    // Profile photo updated successfully
                    echo "Profile photo updated.";
                } else {
                    echo "Error updating profile photo: " . $stmt->error;
                }

                $stmt->close();
            } else {
                // Error moving the file
                echo "Sorry, there was an error moving the uploaded file.";
            }
        } else {
            echo "No file selected.";
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
    <title>Upload Profile Picture</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div>Upload Profile Picture</div>
    <div>
        <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
            <label for="userPhoto">Choose a file:</label>
            <input type="file" name="userPhoto" id="userPhoto"><br>
            <input type="submit" value="Upload" name="uploadPhoto">
        </form>
        <br>
        <a href="settings.php">Return to Settings</a>
    </div>
</body>
</html>
