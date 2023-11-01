<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>REGISTER</h2>
    <div class="container">
        <form action="register_process.php" method="post">
            <div class="form-group">
                <input type="text" name="fullname" placeholder="Full-Name" class="form-control">
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Register" name="submit" class="btn btn-primary">
            </div>
        </form>
        <div><p>Already registerd! <a href="login.php">Login Here</a></p></div>
    </div>
</body>
</html>

<?php
            if(isset($_POST["submit"])){
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $confirmPassword = $_POST["confirm_password"];

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $errors = array();

                if(empty($fullName) OR empty($email) OR empty($password) OR empty($confirmPassword)){
                    array_push($errors, "All Fields are Required");
                }
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    array_push($errors, "Email is not valid.");
                }
                if(strlen($password)<8){
                    array_push($errors, "Password must be at least 8 characters length");
                }
                if($password!==$confirmPassword){
                    array_push($errors, "Password dose not match");
                }
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if($rowCount){
                    array_push($errors, "Email already esist!");

                }

                if(count($errors)>0){
                    foreach($errors as $error){
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }else{
                    $sql = "INSERT INTO users (full-name, email, password) VALUES ( ?, ?, ? )";
                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt(mysqli_stmt), $sql);
                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo"<div class='alert alert-success'>You are registerd successfully.</div>";
                    }else{
                        die("Somthing went wrong");
                    }
                }
            };
        ?>