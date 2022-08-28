<?php
if (isset($_POST['submit'])) {

	//Establish connection to DB
	$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');	

	//Get form data
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$phone = mysqli_real_escape_string($conn, $_POST['phone']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);

	//Other data
	$date = date("Y-m-d H:i:s");

	//Check constraints
	//Check email
	$sql0 = "SELECT * FROM newsletter WHERE Email = '$email' ";
	$result0 = mysqli_query($conn, $sql0);
	$count0 = mysqli_num_rows($result0);
	if ($count0 == 0) {
		//Check phone number
		$sql1 = "SELECT * FROM newsletter WHERE Telephone = '$phone' ";
		$result1 = mysqli_query($conn, $sql1);
		$count1 = mysqli_num_rows($result1);
		if ($count1 == 0) {
			//Everything okay, now insert to db
			$sql2 = "INSERT INTO newsletter (Name, Email, Telephone, Date) VALUES ('$name', '$email', '$phone', '$date') ";
			$result2 = mysqli_query($conn, $sql2);
			$id = mysqli_insert_id($conn);
			if ($result2) {
				echo "Successfully subscribed to our newsletter.";
			}else{
				echo "Sorry an error occured and we could not complete the action please try again later";
			}
		}else{
			echo "Phone number already exits in database";
		}
	}else{
		echo "Email address already exits in database";
	}
}