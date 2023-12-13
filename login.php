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
    <script>
        // Check if the success message is set in the session
        <?php if (isset($_SESSION['success_message'])): ?>
            // Display the success message using JavaScript alert
            alert("<?php echo $_SESSION['success_message']; ?>");
            // Remove the success message from the session
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </script>
</head>

<body>

   <div class="container">
       <div class="sub-container">
           <?php
           if (isset($_POST["login"])) {
               $emailOrUsername = $_POST["email_or_username"];
               $password = $_POST["password"];
           
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
           
               if ($user) {
                   if (password_verify($password, $user["password"])) {
                       session_start();
                       $_SESSION["user"] = "yes";
                       header("Location: index.php");
                       die();
                   } else {
                       echo "<div class='alert alert-danger'>Invalid Email, Username, or Password</div>";
                   }
               } else {
                   echo "<div class='alert alert-danger'>Invalid Email, Username, or Password</div>";
               }
           }
           
           ?>
           <h2 style="margin-bottom: 10px; text-align:center; font-size:30px;">Login</h2>
           <form action="login.php" method="post">
               <div class="form-group">
                   <input type="text" placeholder="Enter Email OR Username" name="email_or_username" class="form-control" <?php echo isset($_POST['email_or_username']) ? htmlspecialchars($_POST['email_or_username']) : ''; ?> required>
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
           <div>
               <p>Not registered yet <a href="registration.php">Register Here</a></p>
           </div>
       </div>
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
