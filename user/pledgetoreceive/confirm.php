<?php
session_start();
if (!$_SESSION['name_member']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$user = $_GET['user'];
$id = $_GET['id'];
$pledgeno0 = $_GET['pledgeno0'];
$pledgeno1 = $_GET['pledgeno1'];
$amount0 = $_GET['amount0'];

$date = date("Y-m-d H:i:s");

//Update matching
$sql = "UPDATE matching SET Status = 'Paid', ConfirmationDate = '$date' WHERE SN = '$id' ";
$result = mysqli_query($conn, $sql);
if ($result) {
    //Set the amount received by the user and check if it is complete
    $sql1 = "SELECT * FROM helpreceived WHERE MatchingNo = '$id' ";
    $result1 = mysqli_query($conn, $sql1);
    $count1 = mysqli_num_rows($result1);
    if ($count1 == 0) {
        $sql2 = "SELECT * FROM helprequest WHERE SN = '$pledgeno0' ";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        if ($row2['Amount'] == 0) {
            $amountpledged = $amount0;
            $amounttoreceive = $amountpledged;
        }else{
            $amountpledged = $row2['Amount'];
            $amounttoreceive = $amountpledged * 2;
        }

        $amountreceived = $amount0;
        $balance = $amounttoreceive - $amountreceived;

        $hours = 120;
        $pledgedate = $row2['PledgeDate'];
        $lastdatetoreceive = date('Y-m-d H:i:s', strtotime('+'.$hours.' hours', strtotime($pledgedate)));

        $sn = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);

        $sql3 = "INSERT INTO helpreceived (SN, MatchingNo, PledgeNo, AmountToReceive, AmountReceived, LastDateToReceive, ModificationDate) VALUES ('$sn', '$id', '$pledgeno0', '$amounttoreceive', '$amountreceived', '$lastdatetoreceive', '$date') ";
        $result3 = mysqli_query($conn, $sql3);

        $sql3_0 = "UPDATE helptoreceive SET AmountReceived = '$totalamountreceived', ModificationDate = '$date' WHERE PledgeNo = '$pledgeno0' ";
        $result3_0 = mysqli_query($conn, $sql3_0);
        if ($balance == 0) {
            $sql4 = "UPDATE helptoreceive SET Status = 'Not Available' WHERE PledgeNo = '$pledgeno0' ";
            $result4 = mysqli_query($conn, $sql4);
        }else{
            $sql4 = "UPDATE helptoreceive SET Status = 'Available' WHERE PledgeNo = '$pledgeno0' ";
            $result4 = mysqli_query($conn, $sql4);
        }
    }else{
        $row1 = mysqli_fetch_assoc($result1);
        $amounttoreceive = $row1['AmountToReceive'];
        $amountreceived = $amount0;
        $amountreceiveddb = $row1['AmountReceived'];
        $totalamountreceived = $amountreceived + $amountreceiveddb;

        $balance = $amounttoreceive - $totalamountreceived;

        $sql3 = "UPDATE helpreceived SET MatchingNo = '$id', AmountReceived = '$totalamountreceived', ModificationDate = '$date' WHERE PledgeNo = '$pledgeno0' ";
        $result3 = mysqli_query($conn, $sql3);

        $sql3_0 = "UPDATE helptoreceive SET AmountReceived = '$totalamountreceived', ModificationDate = '$date' WHERE PledgeNo = '$pledgeno0' ";
        $result3_0 = mysqli_query($conn, $sql3_0);

        if ($balance == 0) {
            $sql4 = "UPDATE helptoreceive SET Status = 'Not Available' WHERE PledgeNo = '$pledgeno0' ";
            $result4 = mysqli_query($conn, $sql4);
        }else{
            $sql4 = "UPDATE helptoreceive SET Status = 'Available' WHERE PledgeNo = '$pledgeno0' ";
            $result4 = mysqli_query($conn, $sql4);
        }
    }

    //Make the payer eligible to receive his/her payment
    $sn = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);

    $amounttoreceive = $amount0 * 2;

    $duration = 5;
    $expirydate = date("Y-m-d H:i:s", strtotime('+'.$duration.' days'));

    $sql5 = "INSERT INTO helptoreceive (SN, PledgeNo, AmountToReceive, AmountReceived, LastDateToReceive, Status, ModificationDate) VALUES ('$sn', '$pledgeno1', '$amounttoreceive', '0', '$expirydate', 'Available', '$date') ";
    $result5 = mysqli_query($conn, $sql5) or die(mysqli_error($conn));

    //Insert referal bonus
    $sql6 = "SELECT * FROM members WHERE Username = '$user' ";
    $result6 = mysqli_query($conn, $sql6);
    $row6 = mysqli_fetch_assoc($result6);
    if (!empty($row6['ReferalID'])) {
        $referalbonusto = $row6['ReferalID'];
        $bonusamt = 0.1 * $amount0;

        $sql7 = "SELECT * FROM members WHERE MyReferalID = '$referalbonusto' ";
        $result7 = mysqli_query($conn, $sql7);
        $row7 = mysqli_fetch_assoc($result7);

        $referalbonusto = $row7['Username'];

        $sn = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXTZ0123456789'), 0, 6);

        $sql8 = "INSERT INTO referalbonus (SN, Username, PledgeNo, Amount, Status) VALUES ('$sn', '$referalbonusto', '$pledgeno1', '$bonusamt', 'Not Paid') ";
        $result8 = mysqli_query($conn, $sql8);
    }


    header('location: index.php');

}

header('location: index.php');

?>