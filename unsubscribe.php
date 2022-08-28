<?php
if (isset($_GET['id'])) {

	//Establish connection to DB
	$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');	

	//Get id
	$id = $_GET['id'];

	//Get user details
	$sql0 = "SELECT * FROM newsletter WHERE SN = '$id' ";
	$result0 = mysqli_query($conn, $sql0);
	$row0 = mysqli_fetch_assoc($result0);

	//Get user data
	$name = $row0['Name']);
	$phone = $row0['Telephone']);
	$email = $row0['Email']);

	//Other data
	$date = date("Y-m-d H:i:s");

	//Check constraints
	//Check email
	$sql1 = "DELETE FROM newsletter WHERE SN = '$id' ";
	$result1 = mysqli_query($conn, $sql1);
	if ($result1) {
		echo "Successfully unsubscribed to our newsletter.";
	}else{
		echo "Sorry an error occured and we could not complete the action please try again later";
	}
}