<?php

// Email Verification
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendMail($email,$v_code)
  {
    require 'PHPmailer/PHPMailer.php';
    require 'PHPmailer/SMTP.php';
    require 'PHPmailer/Exception.php';

    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = 'suman.chaudhary3600@gmail.com';                     //SMTP username
      $mail->Password   = 'jqmo abjs zdlw jnlg';                              //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 465;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
      $mail->setFrom('suman.chaudhary3600@gmail.com', 'suman');
      $mail->addAddress($email);     //Add a recipient
  
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Email Verification';
      $mail->Body    = "Thanks for registration! your verification code is 
      '".$v_code."'";
      $mail->send();
      return true;
  } catch (Exception $e) {
      return false;
  }
  }

?>