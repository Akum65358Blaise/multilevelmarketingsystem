<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}

if (isset($_POST['submit'])){

	$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

	$hours = mysqli_real_escape_string($conn, $_POST['hours']);

	$id = $_GET['id'];

	$sql = "SELECT * FROM matching WHERE SN = '$id' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $lastdate = $row['LastDateToPay'];
    $newlastdate = date('Y-m-d H:i:s', strtotime('+'.$hours.' hours', strtotime($lastdate)));

    $sql1 = "UPDATE matching SET LastDateToPay = '$newlastdate' WHERE SN = '$id' ";
    $result1 = mysqli_query($conn, $sql1);
    if ($result1) {
        header('location: index.php');
    }else{
        echo "Failed to add time";
    }

}