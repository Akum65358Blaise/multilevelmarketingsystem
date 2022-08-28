<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$id = $_GET['id'];
$date = date("Y-m-d H:i:s");

$sql0 = "UPDATE referalbonus SET Status = 'Paid', PaymentDate = '$date' WHERE SN = '$id' ";
$result0 = mysqli_query($conn, $sql0) or die(mysqli_query($conn));
if ($result0) {
    header('location: index.php');
}