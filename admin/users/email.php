<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

if (isset($_POST['submit'])) {
    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    //Get form data
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $member = mysqli_real_escape_string($conn, $_POST['member']);
    $headers = 'From: Confidence Funding Investment donotreply@confidencefundinginvestment.com' . "\r\n" . 
                'Reply-To: info@confidencefundinginvestment.com' . "\r\n" .
                'Content-type: text/html; charset=iso-8859-1';

    if (mail($member, $subject, $message, $headers)) {
        header('location: index.php');
    }else{
        echo  "Failed to send email. Please try again later";
    }
}