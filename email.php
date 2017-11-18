<?php
$email_to = "biochemistrygmcs@gmail.com";
$email_subject = 'Testing to send salary to email';
$email_message = 'Your Salary Statement for the moth of 2017-May';
 
    // create email headers
    $headers = 'From: postmaster@gmcsurat.edu.in'."\r\n".
               'X-Mailer: PHP/' . phpversion();
     $result = mail($email_to, $email_subject, $email_message, $headers); 
 
     if ($result) echo 'Mail accepted for delivery ';
     if (!$result) echo 'Test unsuccessful... ';
?>
