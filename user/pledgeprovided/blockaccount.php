<?php
session_start();
if (!$_SESSION['name_member']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');


$sql0 = "SELECT * FROM users WHERE Username = '".$_SESSION['name_member']."' ";
$result0 = mysqli_query($conn, $sql0);
$row0 = mysqli_fetch_assoc($result0);
$sql = "UPDATE users SET Active = '0' WHERE Username = '".$_SESSION['name_member']."' ";
$result = mysqli_query($conn, $sql);

$sql_matching = "SELECT * FROM matching WHERE Status =  'Not Paid' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Matched = '1' AND Username = '".$_SESSION['name_member']."' ) ";
$result_matching = mysqli_query($conn, $sql_matching);
while ($row_matching = mysqli_fetch_assoc($result_matching)) {
	$pledgeno[] = $row_matching['PledgeNo'];
    $pledgenoto[] = $row_matching['MatchToNo'];
}

$sql2 = "DELETE FROM matching WHERE Status =  'Not Paid' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Matched = '1' AND Username = '".$_SESSION['name_member']."' ) ";
$result2 = mysqli_query($conn, $sql2);

$count = count($pledgeno);
for ($i=0; $i < $count ; $i++) { 
	$id = $pledgeno[$i];
    $sn = $pledgenoto[$i];

    $sql03 = "SELECT * FROM helprequest WHERE SN = '$id' ";
    $result03 = mysqli_query($conn, $sql03);
    $row03 = mysqli_fetch_assoc($result03);

    $amount0 = $row03['Amount'];

    $sql002 = "SELECT * FROM helptoreceive WHERE PledgeNo = '$sn'  ";
    $result002 = mysqli_query($conn, $sql002) or die(mysqli_query($conn));
    $row002 = mysqli_fetch_assoc($result002);

    $amountreceiveddb = $row002['AmountReceived'];

    $newamountreceived = $amountreceiveddb - $amount0;

    $sql30 = "SELECT * FROM helprequest WHERE SN = '$sn' ";
    $result30 = mysqli_query($conn, $sql30);
    $row30 = mysqli_fetch_assoc($result30);
    if ($row30['Amount'] == 0) {
        $sql4 = "DELETE FROM helprequest WHERE SN = '$sn' ";
        $result4 = mysqli_query($conn, $sql4) or die(mysqli_query($conn));
    }else{
        $sql003 = "UPDATE helptoreceive SET Status = 'Available', AmountReceived = '$newamountreceived' WHERE PledgeNo = '$sn' ";
        $result003 = mysqli_query($conn, $sql003) or die(mysqli_query($conn));

        $sql004 = "UPDATE helptoreceive SET Status = 'Available' WHERE PledgeNo = '$sn' ";
        $result004 = mysqli_query($conn, $sql004);
    }

	$sql3 = "DELETE FROM helprequest WHERE SN =  '$id' ";
	$result3 = mysqli_query($conn, $sql3);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Blocked Account - Investment Prime Investment</title>
    <!-- Bootstrap Styles-->
    <link href="/SecurePrimeInvestment/user/assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="/SecurePrimeInvestment/user/assets/css/font-awesome.css" rel="stylesheet" />
     <!-- Morris Chart Styles-->
   
        <!-- Custom Styles-->
    <link href="/SecurePrimeInvestment/user/assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="/SecurePrimeInvestment/user/assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="schoex.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<br><br>
		<div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-body" style="background-color: red; color: white;"> 
                        <h5>Your account has been suspended. <br> Contact our support center at support@secureprimeinvestment.com to verify and activate your account </h5>
                    </div>
                </div>
            </div>
        </div>
        <?php
			session_start();
			session_unset();
			session_destroy();
			header('refresh: 3; url=/SecurePrimeInvestment/index.php');
			exit();
		?>

    <!-- jQuery Js -->
    <script src="/SecurePrimeInvestment/user/assets/js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="/SecurePrimeInvestment/user/assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="/SecurePrimeInvestment/user/assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="/SecurePrimeInvestment/user/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="/SecurePrimeInvestment/user/assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="/SecurePrimeInvestment/user/assets/js/custom-scripts.js"></script>
</body>
</html>