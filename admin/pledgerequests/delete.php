<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$id = $_GET['id'];

$sql00 = "SELECT * FROM users WHERE Username IN (SELECT Username FROM helprequest WHERE SN = '$id') ";
$result00 = mysqli_query($conn, $sql00);
$row00 = mysqli_fetch_assoc($result00);

$sql000 = "SELECT * FROM helprequest WHERE SN = '$id' ";
$result000 = mysqli_query($conn, $sql000);
$row000 = mysqli_fetch_assoc($result000);

$sql0 = "DELETE FROM helprequest WHERE SN = '$id' ";
$result0 = mysqli_query($conn, $sql0) or die(mysqli_query($conn));
if ($result0) {
    header('location: index.php');
}