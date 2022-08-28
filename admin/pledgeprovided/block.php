<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$user = $_GET['user'];
$id = $_GET['id'];

$amount0 = $_GET['amount0'];
$sn = $_GET['sn'];

$sql = "UPDATE users SET Active = '0' WHERE Username = '$user' ";
$result = mysqli_query($conn, $sql);

$sql0 = "SELECT * FROM users WHERE Username = '$user' ";
$result0 = mysqli_query($conn, $sql0);
$row0 = mysqli_fetch_assoc($result0);
$sql_matching = "SELECT * FROM matching WHERE Status =  'Not Paid' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Matched = '1' AND Username = '$user' ) ";
$result_matching = mysqli_query($conn, $sql_matching);
while ($row_matching = mysqli_fetch_assoc($result_matching)) {
	$pledgeno[] = $row_matching['PledgeNo'];
}

$sql00 = "SELECT * FROM matching WHERE PledgeNo = '$sn' ";
$result00 = mysqli_query($conn, $sql00) or die(mysqli_query($conn));
$count00 = mysqli_num_rows($result00);
if ($count00 != 0) {
	$row00 = mysqli_fetch_assoc($result00);
	$newid = $row00['MatchToNo'];
}

$sql2 = "SELECT * FROM helptoreceive WHERE PledgeNo = '$newid'  ";
$result2 = mysqli_query($conn, $sql2) or die(mysqli_query($conn));
$row2 = mysqli_fetch_assoc($result2);

$amountreceiveddb = $row2['AmountReceived'];

$newamountreceived = $amountreceiveddb - $amount0;

$sql3 = "UPDATE helptoreceive SET Status = 'Available', AmountReceived = '$newamountreceived' WHERE PledgeNo = '$newid' ";
$result3 = mysqli_query($conn, $sql3) or die(mysqli_query($conn));

$sql30 = "SELECT * FROM helprequest WHERE SN = '$newid' ";
$result30 = mysqli_query($conn, $sql30);
$row30 = mysqli_fetch_assoc($result30);
if ($row30['Amount'] == 0) {
	$sql4 = "DELETE FROM helprequest WHERE SN = '$newid' ";
	$result4 = mysqli_query($conn, $sql4) or die(mysqli_query($conn));
}else{
	$sql4 = "UPDATE helptoreceive SET Status = 'Available' WHERE PledgeNo = '$newid' ";
	$result4 = mysqli_query($conn, $sql4);
}
$sql5 = "DELETE FROM matching WHERE SN =  '$id' ";
$result5 = mysqli_query($conn, $sql5);

$count = count($pledgeno);
for ($i=0; $i < $count ; $i++) { 
	$id0 = $pledgeno[$i];
	$sql6 = "DELETE FROM helprequest WHERE SN =  '$id0' ";
	$result6 = mysqli_query($conn, $sql6);
}

header('location: index.php');

?>