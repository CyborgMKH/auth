<?php
session_start();
if(isset($_POST['email_verify']))
{
    // Check if 'example_variable' is set and if 30 seconds have passed since its creation
    if (isset($_SESSION['vcode']) && (time() - $_SESSION['vcode']['created_at']) > 30) {
        unset($_SESSION['vcode']);
        $_SESSION['errors']['verification_code']="verification code is expired";
        $_SESSION['errors']['code_expire']=true;
        $_SESSION['access_verification']=true;
        header('location:verification.php');
        exit;
    }

    
    if($_SESSION['vcode']['value']==$_POST['verification_code'])
    {
        $qry="select id from users where email='".$_SESSION['email']."'";
        include "database.php";
        $result=$conn->query($qry);
        $data=$result->fetch_assoc();
        $qry="update users SET is_verified = 1 where id ='".$data['id']."'";
        if($conn->query($qry) ===true)
        {
            $conn->close();
            $_SESSION['is_verified']=true;
            $_SESSION["user"] = "yes";
            header('location:index.php');
        }
    }
    else
    {
        $_SESSION['errors']['verification_code']="verification failed please recheck your verification code";
        $_SESSION['access_verification']=true;
        header('location:verification.php');
    }
    
}

include "sendmail.php";
if(isset($_POST['resend']))
{
    $v_code=bin2hex(random_bytes(6));
    $email=$_SESSION['email'];
    if(sendMail($email,$v_code))
    {
        $_SESSION['vcode']['value']=$v_code;
        $_SESSION['vcode']['created_at']=time();
        $_SESSION['resend_code']=true;
        $_SESSION['access_verification']=true;
        header('location:verification.php');
    }
    

}

?>