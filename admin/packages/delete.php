<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$id = $_GET['id'];

$sql0 = "DELETE FROM package WHERE SN = '$id' ";
$result0 = mysqli_query($conn, $sql0) or die(mysqli_query($conn));
if ($result0) {
    header('location: index.php');
}