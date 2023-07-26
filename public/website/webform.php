<?php
  $message_sent = false;

  if (isset($_POST['email']) && $_POST['email'] != '') {
    // code...
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
      // code...
      $userName = $_POST['name'];
      $userEmail = $_POST['email'];
      $userPhone = $_POST['phone'];
      $serviceType = $_POST['service'];
      $message = $_POST['message'];

      $from = 'contact@domainname.com';
      $to = "f.usmani88@gmail.com";
      $body = "";

      $body .= "From: ".$userName. "\r\n";
      $body .= "Email: ".$userEmail. "\r\n";
      $body .= "Phone: ".$userPhone. "\r\n";
      $body .= "Service: ".$serviceType. "\r\n";
      $body .= "Message: ".$message. "\r\n";

      mail($to,$messageSubject,$body);

      header("Location: contact.html");

      $message_sent = true;

      if($message_sent == true) {
        header('Location: success.html');
      }
    }
  }
 ?>
