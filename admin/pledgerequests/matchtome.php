<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location:   /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');
$duration = 1;
$pledgeno = $_GET['pledgeno'];
$amount0 = $_GET['amount0'];
$date = date("Y-m-d H:i:s");
$sn = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);
$expirydate = date("Y-m-d H:i:s", strtotime('+'.$duration.' day'));

//Get your username as a member
$sql_0 = "SELECT * FROM members WHERE Email = '".$_SESSION['email_admin']."' ";
$result_0 = mysqli_query($conn, $sql_0);
$row_0 = mysqli_fetch_assoc($result_0);
$matchto = $row_0['Username'];

//Create an abritary pledge for me
$pledgeno0 = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);
$sql_01 = "INSERT INTO helprequest (SN, Username, Amount, PledgeDate, Matched) VALUES ('$pledgeno0', '$matchto', '0', '$date', '1') ";
$result_01 = mysqli_query($conn, $sql_01);

//Assume you had been match (to your self) and that you have paid the money already
$sn0 = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);
$sql_02 = "INSERT INTO matching (SN, PledgeNo, MatchTo, MatchToNo, MatchDate, LastDateToPay, Status, ConfirmationDate) VALUES ('$sn0', '$pledgeno0', '$matchto', '$pledgeno0', '$date', '$date', 'Paid', '$date') ";
$result_02 = mysqli_query($conn, $sql_02);

//I have been confirmed so now i can receive the payment this user is pledging to pay
$id = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);
$duration0 = 2;
$expirydate0 = date("Y-m-d H:i:s", strtotime('+'.$duration0.' days'));
$sql_03 = "INSERT INTO helptoreceive (SN, PledgeNo, AmountToReceive, AmountReceived, LastDateToReceive, Status, ModificationDate) VALUES ('$id', '$pledgeno0', '$amount0', '0', '$expirydate0', 'Not Available', '$date') ";
$result_03 = mysqli_query($conn, $sql_03);

//I now have everything i need
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