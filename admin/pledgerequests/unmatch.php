<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$amount0 = $_GET['amount0'];
$id = $_GET['id'];

$sql0 = "UPDATE helprequest SET Matched = '0' WHERE SN = '$id' ";
$result0 = mysqli_query($conn, $sql0) or die(mysqli_query($conn));

$sql00 = "SELECT * FROM matching WHERE PledgeNo = '$id' ";
$result00 = mysqli_query($conn, $sql00) or die(mysqli_query($conn));
$count00 = mysqli_num_rows($result00);
if ($count00 != 0) {
	$row00 = mysqli_fetch_assoc($result00);
	$newid = $row00['MatchToNo'];
}


if ($result0) {
	$sql1 = "DELETE FROM matching WHERE PledgeNo = '$id' ";
	$result1 = mysqli_query($conn, $sql1) or die(mysqli_query($conn));

	$sql2 = "SELECT * FROM helptoreceive WHERE PledgeNo = '$newid'  ";
	$result2 = mysqli_query($conn, $sql2) or die(mysqli_query($conn));
	$row2 = mysqli_fetch_assoc($result2);

	$amountreceiveddb = $row2['AmountReceived'];

	$newamountreceived = $amountreceiveddb - $amount0;

	$sql3 = "SELECT * FROM helprequest WHERE SN = '$newid' ";
	$result3 = mysqli_query($conn, $sql3);
	$row3 = mysqli_fetch_assoc($result3);
	if ($row3['Amount'] == 0) {
		$sql4 = "DELETE FROM helprequest WHERE SN = '$newid' ";
		$result4 = mysqli_query($conn, $sql4) or die(mysqli_query($conn));
	}else{
		$sql4 = "UPDATE helptoreceive SET Status = 'Available', AmountReceived = '$newamountreceived' WHERE PledgeNo = '$newid' ";
		$result4 = mysqli_query($conn, $sql4) or die(mysqli_query($conn));
	}

	
    //Get username and email of the supposed receiver
    $sql_0 = "SELECT * FROM users WHERE Username IN (SELECT Username FROM helprequest WHERE SN = '$newid' ) ";
    $result_0 = mysqli_query($conn, $sql_0);
    $row_0 = mysqli_fetch_assoc($result_0);

    //Get username and email of the supposed payer
    $sql_00 = "SELECT * FROM users WHERE Username IN (SELECT Username FROM helprequest WHERE SN = '$id' ) ";
    $result_00 = mysqli_query($conn, $sql_00);
    $row_00 = mysqli_fetch_assoc($result_00);
    $name_block = $row_00['Username'];
    $to_block = $row_00['Email'];	
	header('location: index.php');
   
}