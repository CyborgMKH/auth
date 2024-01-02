<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
    <script>
        // Check if the success message is set in the session
        <?php if (isset($_SESSION['is_verified']) &&$_SESSION['is_verified']==true) : ?>
            // Display the success message using JavaScript alert
            alert("verified successfully");
            // Remove the success message from the session
            <?php unset($_SESSION['is_verified']); ?>
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        
        <h2 style="color:white; margin-right:5px">Welcome to dashboard</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    
    
</body>
</html>