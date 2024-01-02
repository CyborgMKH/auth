<?php
session_start();

if(isset($_GET['email']))
{
    $email=$_GET['email'];
    include "database.php";
    $qry="select id,email from users where email='".$email."'";
    $result=$conn->query($qry);
    $conn->close();
    $data=$result->fetch_assoc();
}
else
{
     // Redirect to a custom error page with HTTP status code 404
     http_response_code(404);
     include('404.html'); // Replace '404.html' with the actual path to your custom error page
     exit;
}

if(isset($_POST['change_email']))
{

    $email=$_POST['email'];
    include "database.php";
    $qry = "select * from users where email='" . $email . "'";
    $result = $conn->query($qry);
    $rowCount = mysqli_num_rows($result);
    if($email==$data['email'])
    {
        $_SESSION['errors']['email']="try different email";
    }
    else if ($rowCount > 0) {
        $_SESSION['errors']['email'] = 'Email already exists';
    }

    if (!isset($_SESSION['errors'])) {
        include "sendmail.php";
        $v_code=bin2hex(random_bytes(6));
        $id=$_POST['id'];
        include "database.php";
        $qry="update users set email='".$email."' where id='".$id."'";
        if($conn->query($qry) ===true && sendMail($email,$v_code))
        {
            $conn->close();
            $_SESSION['email']=$email;
            $_SESSION['vcode']['value']=$v_code;
            $_SESSION['vcode']['created_at']=time();
            $_SESSION['access_verification']=true;
            header('location:verification.php');
            exit();
        }
    }
    else
    {
        $data['email']=$email;
    }
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

            <h2 style="margin-bottom: 10px; text-align:center; font-size:30px;">Change Email</h2>
            <form method="post">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $data['email'] ?? '' ?>" required>
                    <?php if (isset($_SESSION['errors']['email'])) :  ?>
                        <span class="error">*<?php echo $_SESSION['errors']['email'] ?></span>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Change" name="change_email">
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
if (isset($_SESSION['old_data'])) {
    unset($_SESSION['old_data']);
}

?>