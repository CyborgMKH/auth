<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<?php include "database.php" ?>
<?php include "sendmail.php" ?>
<?php
if (isset($_POST["submit"])) {
    $data = $_POST;
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $v_code=bin2hex(random_bytes(6));

    //using regex and regular expression for checking password strength and stop user registring with weak password
    $passwordCheckRegex = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$^";

    //saving password in the form of like encoded form in to the database
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Verify reCAPTCHA
    $recaptchaSecretKey = "6LcJLiUpAAAAAMcM0rV_5cYMgyUZJbrKZeVojU18";
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaVerificationUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecretKey}&response={$recaptchaResponse}";
    $recaptchaResponseData = json_decode(file_get_contents($recaptchaVerificationUrl));

    // checking the existing email
    $qry = "select * from users where email='" . $email . "'";
    $qry2 = "select * from users where username='" . $username . "'";
    $result = $conn->query($qry);
    $result2 = $conn->query($qry2);
    $rowCount = mysqli_num_rows($result);
    $rowCountusername = mysqli_num_rows($result2);

    //server side form validations
    if (empty($username)) {
        $_SESSION['errors']['username'] = 'Username is required';
    } else if ($rowCountusername > 0) {
        $_SESSION['errors']['username'] = 'The username is taken';
    }
    if (empty($email)) {
        $_SESSION['errors']['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = 'Email is invalid';
    } else if ($rowCount > 0) {
        $_SESSION['errors']['email'] = 'Email already exists';
    }
    if (empty($password)) {
        $_SESSION['errors']['password'] = 'Password is required';
    } else if (!preg_match($passwordCheckRegex, $password)) {
        $_SESSION['errors']['password'] = "weak password";
    } else if ($password != $confirmPassword) {
        $_SESSION['errors']['password'] = 'Password doesnot matched';
    }
    if (!$recaptchaResponseData->success) {
        $_SESSION['errors']['captcha'] = 'reCAPTCHA verification failed';
    }

    if (!isset($_SESSION['errors'])) {
        $qry = "insert into users (username,email,password) values('" . $username . "','" . $email . "','" . $passwordHash . "')";
        if($conn->query($qry) ===true && sendMail($email,$v_code) )
        {
            $_SESSION['vcode']['value']=$v_code;
            $_SESSION['vcode']['created_at']=time();
            $_SESSION['email']=$email;
            $conn->close();
            // Set success message in session
            $_SESSION['success_message'] = "User Registration successfuly!";
            //redirect to the login page when the registration is success
            $_SESSION['access_verification']=true;
            header('location:verification.php');
        }
    } else {
        $_SESSION['old_data'] = $data;
    }
}

if(isset($_GET['change_email']))
{
    $email=$_GET['change_email'];
    include "database.php";
    $qry="select id,username,email from users where email='".$email."'";
    $result=$conn->query($qry);
    $data=$result->fetch_assoc();
    $_SESSION['old_data'] = $data;
}
?>
<!-- user registration form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- for captca validation -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="JS/strength.js"></script>

</head>

<body>
    <div class="container">

        <div class="sub-container">

            <h2 style="margin-bottom: 10px; text-align:center; font-size:30px;">Registration</h2>
            <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $_SESSION['old_data']['username'] ?? '' ?>" required>
                    <?php if (isset($_SESSION['errors']['username'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['errors']['username'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $_SESSION['old_data']['email'] ?? '' ?>" required>
                    <?php if (isset($_SESSION['errors']['email'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['errors']['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" oninput="checkPasswordStrength(this.value,'password')" value="<?php echo $_SESSION['old_data']['password'] ?? '' ?>" id="Showpassword" required>
                    <?php if (isset($_SESSION['errors']['password'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['errors']['password'] ?></span>
                    <?php endif; ?>
                    <p id="password" class="password-strength" style="margin-top: 5px;"></p>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" oninput="checkPasswordStrength(this.value,'confirm_password')" value="<?php echo $_SESSION['old_data']['confirm_password'] ?? '' ?>" id="Showpassword2" required>
                    <p id="confirm_password" class="password-strength" style="margin-top: 5px;"></p>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="showPasswordCheckbox"> Show Password
                    </label>
                </div>

                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LcJLiUpAAAAAE_TA5toLb0D2z1hwAOzd77Ww687"></div>
                    <?php if (isset($_SESSION['errors']['captcha'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['errors']['captcha'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>
            <div>
                <div>
                    <p>Already Registered <a href="login.php">Login Here</a></p>
                </div>
            </div>
        </div>
    </div>


    <script>
        // JavaScript for the operation to show password in the form and emplemented in check box 
        document.addEventListener('DOMContentLoaded', function() {
            const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');
            const passwordInput = document.getElementById('Showpassword');
            const passwordInput2 = document.getElementById('Showpassword2');

            showPasswordCheckbox.addEventListener('change', function() {
                passwordInput.type = showPasswordCheckbox.checked ? 'text' : 'password';
                passwordInput2.type = showPasswordCheckbox.checked ? 'text' : 'password';
            });
        });
    </script>
</body>

</html>


<?php

if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
if (isset($_SESSION['old_data'])) {
    unset($_SESSION['old_data']);
}

?>