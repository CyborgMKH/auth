<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>

<?php
if (isset($_POST["login"])) {
    $emailOrUsername = $_POST["email_or_username"];
    $password = $_POST["password"];
    $data = $_POST;

    require_once "database.php";

    // Check if the entered value is a valid email address
    if (filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM users WHERE email = ?";
    } else {
        // If not a valid email, treat it as a username
        $sql = "SELECT * FROM users WHERE username = ?";
    }

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $emailOrUsername);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // Server-side form validations
    // empty username or email
    //Invalid Email or Username
    if (empty($emailOrUsername)) {
        $_SESSION['error']['email_or_username'] = 'Username or Email is required';
    } else if (!$user) {
        $_SESSION['error']['email_or_username'] = 'Invalid Email or Username';
    }

    if (empty($password)) {
        $_SESSION['error']['password'] = 'Password is required';
    }

    if ($user && !password_verify($password, $user["password"])) {
        $_SESSION['error']['password'] = 'Invalid Password';
    }
    // die($user["is_verified"]);

    if(!$user["is_verified"])
    {
        include "sendmail.php";
        $v_code=bin2hex(random_bytes(6));
        $email=$user['email'];
        if(sendMail($email,$v_code))
        {
            $_SESSION['email']=$email;
            $_SESSION['vcode']['value']=$v_code;
            $_SESSION['vcode']['created_at']=time();
            $_SESSION['access_verification']=true;
            header('location:verification.php');
            exit();
        }
    }

    $_SESSION['old_data'] = $data;

    if (!isset($_SESSION['error'])) {
        // Successful login
        $_SESSION["user"] = "yes";
        header("Location: index.php");
        die();
    } else {
        // Redirect to the login page with errors
        header("Location: login.php");
        die();
    }
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
    <script>
        // Check if the success message is set in the session
        <?php if (isset($_SESSION['passwordReset']) &&$_SESSION['passwordReset']==true) : ?>
            // Display the success message using JavaScript alert
            alert("Password Reset successfully! check your email");
            // Remove the success message from the session
            <?php unset($_SESSION['passwordReset']); ?>
        <?php endif; ?>
    </script>
  
</head>

<body>

    <div class="container">
        <div class="sub-container">

            <h2 style="margin-bottom: 10px; text-align:center; font-size:30px;">Login</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Enter Email OR Username" name="email_or_username" class="form-control" <?php echo isset($_POST['email_or_username']) ? htmlspecialchars($_POST['email_or_username']) : ''; ?> value="<?php echo $_SESSION['old_data']['email_or_username'] ?? '' ?>" required>
                    <?php if (isset($_SESSION['error']['email_or_username'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['error']['email_or_username'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="password" placeholder="Enter Password" id="password" name="password" class="form-control" <?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?> value="<?php echo $_SESSION['old_data']['password'] ?? '' ?>" required>
                    <?php if (isset($_SESSION['error']['password'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['error']['password'] ?></span>
                    <?php endif; ?>
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
            <div>
                <p>Not registered yet <a href="registration.php">Register Here</a></p>
            </div>
            <div>
                <a href="forgotpassword.php">Forgot Password ?</a>
            </div>
        </div>
    </div>


</body>

</html>

<script>
    //Js for show entered password
    document.addEventListener('DOMContentLoaded', function() {
        const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');
        const passwordInput = document.getElementById('password');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = showPasswordCheckbox.checked ? 'text' : 'password';
        });
    });
</script>
<?php
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
if (isset($_SESSION['old_data'])) {
    unset($_SESSION['old_data']);
}
?>