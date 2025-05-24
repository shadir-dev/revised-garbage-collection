<?php
$to = "shadrackmuinde23@gmail.com.com";  // Use your real email here to test
$subject = "Test mail";
$message = "This is a test message to check mail() function.";
$headers = "From: alshadir3@gmail.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail sent successfully.";
} else {
    echo "Mail sending failed.";
}
?>
