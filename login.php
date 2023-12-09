<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">  
</head>
<body>

    <div style="text-align: center;">
        <h1 style="margin-bottom: 10px;">Login</h1>
    </div>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
           $email = $_POST["email"];
           $username = $_POST["username"];
           $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email' or username = '$username'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alert alert-danger'>Invalid Email, Username, or Password</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Invalid Email, Username, or Password</div>";
            }
        }
        ?>
      <form action="login.php" method="post">
        <div class="form-group">
            <input type="text" placeholder="Enter Email OR Username" name="email" class="form-control" <?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?> required>
        </div>
        <div class="form-group">
            <input type="password" placeholder="Enter Password" id="password" name="password" class="form-control" <?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?> required>
        </div>
        <div class="form-group">
                <label>
                    <input type="checkbox" id="showPasswordCheckbox"> Show Password
                </label>
            </div>
        <div class="form-btn">
            <input type="submit" value="Login" name="login" class="btn btn-primary">
        </div>
      </form>
     <div><p>Not registered yet <a href="registration.php">Register Here</a></p></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');
            const passwordInput = document.getElementById('password');

            showPasswordCheckbox.addEventListener('change', function() {
                passwordInput.type = showPasswordCheckbox.checked ? 'text' : 'password';
            });
        });
    </script>
</body>
</html>
<?php
