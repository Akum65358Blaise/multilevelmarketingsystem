<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');
$duration = 1;
$pledgeno = $_GET['pledgeno'];
$pledgeno0 = $_GET['pledgeno0'];
$matchto = $_GET['matchto'];
$id = $_GET['id'];
$amount0 = $_GET['amount0'];
$date = date("Y-m-d H:i:s");
$sn = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);
$expirydate = date("Y-m-d H:i:s", strtotime('+'.$duration.' day'));

$sql0 = "UPDATE helprequest SET Matched = '1' WHERE SN = '$pledgeno' ";
$result0 = mysqli_query($conn, $sql0) or die(mysqli_query($conn));
if ($result0) {
	$sql1 = "INSERT INTO matching (SN, PledgeNo, MatchTo, MatchToNo, MatchDate, LastDateToPay, Status) VALUES ('$sn', '$pledgeno', '$matchto', '$pledgeno0', '$date', '$expirydate', 'Not Paid') ";
	$result1 = mysqli_query($conn, $sql1) or die(mysqli_query($conn));

	$sql2 = "SELECT * FROM helprequest WHERE SN = '$pledgeno'  ";
	$result2 = mysqli_query($conn, $sql2) or die(mysqli_query($conn));
	$row2 = mysqli_fetch_assoc($result2);

	$sql3 = "SELECT * FROM helptoreceive WHERE SN = '$id'  ";
	$result3 = mysqli_query($conn, $sql3) or die(mysqli_query($conn));
	$row3 = mysqli_fetch_assoc($result3);

	$amountdb = $row3['AmountReceived'];
	$totalamount = $amountdb + $amount0;

	$balance = $row3['AmountToReceive'] - $totalamount;

	if ($row3['AmountToReceive'] > $totalamount) {
		$sql4 = "UPDATE helptoreceive SET Status = 'Available', AmountReceived = '$totalamount' WHERE SN = '$id' ";
		$result4 = mysqli_query($conn, $sql4) or die(mysqli_query($conn));
	}else{
		$sql4 = "UPDATE helptoreceive SET Status = 'Not Available', AmountReceived = '$totalamount' WHERE SN = '$id' ";
		$result4 = mysqli_query($conn, $sql4) or die(mysqli_query($conn));
	}
    header('location: index.php');
}