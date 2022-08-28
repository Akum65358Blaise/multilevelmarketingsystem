<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
if (isset($_POST['submit'])) {
    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    //Get id
    $id = $_GET['id'];

    //Get form data
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $visibility = mysqli_real_escape_string($conn, $_POST['visibility']);

    //Other data
    $date = date("Y-m-d H:i:s");

    $sql0 = "UPDATE package SET Amount = '$amount', Active = '$visibility' WHERE SN = '$id' ";
    $result0 = mysqli_query($conn, $sql0) or die(mysqli_error($conn));
    if ($result0) {
        header('location: index.php');
    }
}



?>