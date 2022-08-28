<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
if (isset($_POST['submit'])) {
    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    //Get id
    $id = $_GET['id'];

    $sql00 = "SELECT * FROM news WHERE SN = '$id' ";
    $result00 = mysqli_query($conn, $sql00);
    $row00 = mysqli_fetch_assoc($result00);

    if (empty($_POST['news'])) {
        $news = $row00['News'];
    }else{
        $news = mysqli_real_escape_string($conn, $_POST['news']);
    }

    $visibility = mysqli_real_escape_string($conn, $_POST['visibility']);

    //Other data
    $date = date("Y-m-d H:i:s");

    $sql0 = "UPDATE news SET News = '$news', Active = '$visibility' WHERE SN = '$id' ";
    $result0 = mysqli_query($conn, $sql0) or die(mysqli_error($conn));
    if ($result0) {
        if ($visibility == 1) {
            $sql2 = "UPDATE news SET Visibility = '0' WHERE SN != '$id' ";
            $result2 = mysqli_query($conn, $sql2);
        }
        header('location: index.php');
    }
}



?>