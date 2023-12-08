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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- for captca validation -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="JS/pwgenerator.js"></script>

</head>

<body>
    <div style="text-align: center;">
        <h1 style="margin-bottom: 10px;">Registration</h1>
    </div>
    <div class="container">
        <?php include "database.php" ?>
        <?php
        if (isset($_POST["submit"])) {
            $data = $_POST;
            $fullName = $_POST["full_name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirm_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Verify reCAPTCHA
            $recaptchaSecretKey = "6LcJLiUpAAAAAMcM0rV_5cYMgyUZJbrKZeVojU18";
            $recaptchaResponse = $_POST['g-recaptcha-response'];

            $recaptchaVerificationUrl = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecretKey}&response={$recaptchaResponse}";
            $recaptchaResponseData = json_decode(file_get_contents($recaptchaVerificationUrl));

            // checking the existing email
            $qry = "select * from users where email='" . $email . "'";
            $result = $conn->query($qry);
            $rowCount = mysqli_num_rows($result);

            if (empty($fullName)) {
                $_SESSION['errors']['fullname'] = 'Full Name is required';
            } else if (empty($email)) {
                $_SESSION['errors']['email'] = 'Email is required';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['errors']['email'] = 'Email is invalid';
            } else if ($rowCount > 0) {
                $_SESSION['errors']['email'] = 'Email already exists';
            } else if (empty($password)) {
                $_SESSION['errors']['password'] = 'Password is required';
            } else if ($password != $confirmPassword) {
                $_SESSION['errors']['password'] = 'Password doesnot matched';
            } else if (!$recaptchaResponseData->success) {
                $_SESSION['errors']['captcha'] = 'reCAPTCHA verification failed';
            }

            if (!isset($_SESSION['errors'])) {
                $qry = "insert into users values('','" . $fullName . "','" . $email . "','" . $passwordHash . "')";
                $conn->query($qry);
                $conn->close();
                header('location:login.php');
            } else {
                $_SESSION['old_data'] = $data;
            }
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="full_name" placeholder="Full Name" value="<?php echo $_SESSION['old_data']['full_name'] ?? '' ?>">
                <?php if (isset($_SESSION['errors']['fullname'])) :  ?>
                    <p class="alert alert-danger"><?php echo $_SESSION['errors']['fullname'] ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo $_SESSION['old_data']['email'] ?? '' ?>">
                <?php if (isset($_SESSION['errors']['email'])) :  ?>
                    <p class="alert alert-danger"><?php echo $_SESSION['errors']['email'] ?></p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" oninput="checkPasswordStrength(this.value,'password')" onclick="setPassword(this, 'showPasswordCheckbox')" value="<?php echo $_SESSION['old_data']['password'] ?? '' ?>">
                <?php if (isset($_SESSION['errors']['password'])) :  ?>
                    <p class="alert alert-danger"><?php echo $_SESSION['errors']['password'] ?></p>
                <?php endif; ?>
                <p id="password" class="password-strength" style="margin-top: 5px;"></p>
            </div>
            <div class="form-group">
                <input type="password" class="form-control"  name="confirm_password" placeholder="Confirm Password" oninput="checkPasswordStrength(this.value,'confirm_password')" onclick="setPassword(this, 'showPasswordCheckbox')" value="<?php echo $_SESSION['old_data']['confirm_password'] ?? '' ?>">
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
                    <p class="alert alert-danger"><?php echo $_SESSION['errors']['captcha'] ?></p>
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