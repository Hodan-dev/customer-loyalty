<?php
session_start();
include "connection.php";

if (isset($_GET["message"])) {
    $message = "<font color='red'>" . $_GET["message"] . "</font>";
}

if(isset($_POST["register"])) {

    extract ($_POST);

    if($password != $confirm) {
        $info = "<font color='red'>Password fields must match!</font>";
    } else {

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', PASSWORD('$password'))";

    if ($conn->query($sql)) {
        $info = "<font color='green'>User has been registered successfully</font>";
    } else {
        $info = "<font color='red'>User has been registered!" . $conn->error . "</font>";
    }
    }
}

if(isset($_POST["login"])) {

extract($_POST);

$sql = "SELECT * FROM users WHERE email = '$login_email' AND password = PASSWORD('$login_password')";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    session_regenerate_id();
    $user_session_id = session_id();
    $_SESSION["user_session_id"] = $user_session_id;
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["name"] = $row["name"];
    $_SESSION["email"] = $row["email"];

    if ($row["status"] == "Active") {
        $conn->query("UPDATE users SET user_session = '$user_session_id' WHERE id = " . $row["id"]);
        header("Location: index.php");
    } else {
        $message = "<font color='red'>User has been locked!</font>";
    }

    // $message = "<font color='green'>User logged in successfully</font>";
} else {
    $message = "<font color='red'>User email or password is incorrect!</font>";
}

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="css/style.css">
         
    <title>Login & Registration Form</title> 
</head>
<body>
    
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login</span>
                <div><?php if(isset($message)) echo $message; ?></div>
                <form action="" method="post">
                    <div class="input-field">
                        <input type="text" placeholder="Enter your email" id="login_email" name="login_email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" class="password" placeholder="Enter your password" id="login_password" name="login_password" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="logCheck">
                            <label for="logCheck" class="text">Remember me</label>
                        </div>
                        
                        <a href="#" class="text">Forgot password?</a>
                    </div>

                    <div class="input-field button">
                        <input type="submit" id="login" name="login" value="Login">
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">Not a member?
                        <a href="#" class="text signup-link">Signup Now</a>
                    </span>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="form signup">
                <span class="title">Registration</span>

                <div style="margin-top: 1em;"><?php if(isset($info)) echo $info; ?></div>
                <form action="" method="post">
                    <div class="input-field">
                        <input type="text" placeholder="Enter your name" id="name" name="name" required>
                        <i class="uil uil-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="text" placeholder="Enter your email" id="email" name="email" required>
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" class="password" placeholder="Create a password" id="password" name="password" required>
                        <i class="uil uil-lock icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" class="password" placeholder="Confirm a password" id="confirm" name="confirm" required>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>

                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="termCon" name="email" value="agree">
                            <label for="termCon" class="text">I accepted all terms and conditions</label>
                        </div>
                    </div>

                    <div class="input-field button">
                        <input type="submit" id="register" name="register" value="Signup">
                    </div>
                </form>

                <div class="login-signup">
                    <span class="text">Already a member?
                        <a href="#" class="text login-link">Login Now</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

     <script src="js/script.js"></script> 
</body>
</html>