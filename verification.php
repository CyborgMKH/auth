<?php

session_start();

if(!isset($_SESSION['access_verification']))
{
    // Redirect to a custom error page with HTTP status code 404
    http_response_code(404);
    include('404.html'); // Replace '404.html' with the actual path to your custom error page
    exit;
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>

<body>

    <div class="container">
        <div class="sub-container">

            <h2 style="margin-bottom: 10px; text-align:center; font-size:30px; font-weight: 600;">Verify Your Email Address</h2>
            <h4 style="text-align: center;">A verification code has been sent to <br><span style="font-size: 20px; font-weight: bold;"><?php echo $_SESSION['email']??''  ?></span></h4>
            <?php 
            if(isset($_SESSION['errors']['code_expire']))
            {

            ?>
            <p>
                please resend code
            </p>
            <?php
            }
            else
            {
            ?>

            <p>
                Please check your inbox and enter the verification code below to verify your email address.
                The code will expire in <span id="expiration-time" >30</span> sec
            </p>
            <?php 
            }
            if(isset($_SESSION['resend_code']) && $_SESSION['resend_code']==true)
            {
                ?>

                <p style="color: red;">code resend successfully *</p>
                <?php
            }
            unset($_SESSION['resend_code']);

            ?>

            <p id="error" style="display: none; color: red;">verification code expired! <br>please resend code *</p>
            <form action="processverification.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Enter Your verification code" name="verification_code" class="form-control" required>
                    <?php if (isset($_SESSION['errors']['verification_code'])) :  ?>
                            <span class="error">*<?php echo $_SESSION['errors']['verification_code'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-btn">
                    <input style="width: 100%;" type="submit" value="verify" name="email_verify" class="btn btn-primary">
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <a style="background: none; border: none; cursor: pointer; color: blue; text-decoration: underline;" id="<?php echo isset($_SESSION['errors']['code_expire'])?'resend-button-true':'resend-button'; ?>">Resend code</a>
                    <a href="change_email.php?email=<?php echo $_SESSION['email'] ?>" style="background: none; border: none; cursor: pointer; color: blue; text-decoration: underline;">Change Email</a>
                </div>
            </form>
            
        </div>
    </div>

    <script>
    // Get the current time
    var currentTime = new Date();

    // Add 30 seconds to the current time
    var expirationTime = new Date(currentTime.getTime() + 30 * 1000);


    $(document).ready(function()
    {   
        $('#resend-button').click(function()
        {
            if(expirationTime >= new Date() )
            {
                alert("Wait until expiration time!");
                console.log(new Date());
                console.log(expirationTime);
            }
            else
            {
                $(this).closest('form').append('<input type="hidden" name="resend" value="true">').submit();
            }
        });
        $('#resend-button-true').click(function()
        {
            $(this).closest('form').append('<input type="hidden" name="resend" value="true">').submit();
        });
    });
    function resendCode(e) {
        
    }

    

</script>
<script>
    // Set the initial countdown value
    var countdown = 30;

    // Update the countdown every second
    var countdownInterval = setInterval(function() {
        // Update the countdown displayed in the span element
        document.getElementById('expiration-time').textContent = countdown;

        // Decrement the countdown
        countdown--;

        // Check if the countdown has reached 0
        if (countdown < 0) {
            // Clear the interval when the countdown reaches 0
            clearInterval(countdownInterval);

            // Perform any action when the countdown reaches 0 (e.g., show a message)
            // alert("Verification code expired!");
            document.getElementById('error').style.display="block";
        }
    }, 1000); // Update every 1000 milliseconds (1 second)
</script>

</body>

</html>

<?php

if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
if(isset($_SESSION['access_verification']))
{
    unset($_SESSION['access_verification']);
}

?>