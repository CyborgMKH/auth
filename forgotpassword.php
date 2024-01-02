<?php
session_start();
function generateRandomPassword($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    
    return $password;
}



if(isset($_POST['forgot_password']))
{

    $email=$_POST['email'];
    include "database.php";
    $qry = "select * from users where email='" . $email . "'";
    $result = $conn->query($qry);
    $data=$result->fetch_assoc();
    $rowCount = mysqli_num_rows($result);
    // die($rowCount);

    if (!$rowCount > 0) {
        $_SESSION['errors']['email'] = 'Email not found';
    }

    if (!isset($_SESSION['errors'])) {
        include "sendPasswordMail.php";
        // Example: Generate a random password with a length of 12 characters
        $generatedPassword = generateRandomPassword(8);
        $passwordHash = password_hash($generatedPassword, PASSWORD_DEFAULT);
        $id=$data['id'];
        include "database.php";
        $qry="update users set password='".$passwordHash."' where id='".$id."'";
        if($conn->query($qry) ===true && sendPasswordMail($email,$generatedPassword))
        {
            $conn->close();
            $_SESSION['passwordReset']=true;
            header("location:login.php");
            exit();
        }
    }
    else
    {
        $data['email']=$email;
    }
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
    <script src="JS/strength.js"></script>

</head>

<body>
    <div class="container">

        <div class="sub-container">

            <h2 style="margin-bottom: 10px; text-align:center; font-size:30px;">Enter Email</h2>
            <form method="post">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $data['email'] ?? '' ?>" required>
                    <?php if (isset($_SESSION['errors']['email'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['errors']['email'] ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="find" name="forgot_password">
                    <a href="login.php" class="btn btn-danger">cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>

<?php
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

?>