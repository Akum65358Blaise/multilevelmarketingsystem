<?php
if ($_POST) {
	//Establish connection to DB
	$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

	

	//Get form data
	$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
	$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$phone = mysqli_real_escape_string($conn, $_POST['phone']);
	$referalid = mysqli_real_escape_string($conn, $_POST['referalid']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	$conpassword = mysqli_real_escape_string($conn, $_POST['conpassword']);

	//Other data
	$date = date("Y-m-d H:i:s");
	$myreferalid = $firstname . ' ' . $lastname;

	//Check contrainst
	//First name - last name (myreferalid)
	$sql0 = "SELECT * FROM members WHERE MyReferalID = '$myreferalid' OR (FirstName = '$firstname' AND LastName = '$lastname') ";
	$result0 = mysqli_query($conn, $sql0);
	$count0 = mysqli_num_rows($result0);
	if ($count0 == 0) {
		//Username
		$sql1 = "SELECT * FROM members WHERE Username = '$username' ";
		$result1 = mysqli_query($conn, $sql1);
		$count1 = mysqli_num_rows($result1);
		if ($count1 == 0) {
			//Email
			$sql2 = "SELECT * FROM members WHERE Email = '$email' ";
			$result2 = mysqli_query($conn, $sql2);
			$count2 = mysqli_num_rows($result2);
			if ($count2 == 0) {
				//Phone
				$sql3 = "SELECT * FROM members WHERE Phone = '$phone' ";
				$result3 = mysqli_query($conn, $sql3);
				$count3 = mysqli_num_rows($result3);
				if ($count3 == 0) {
					//Check if ref user exits
					if (!empty($_POST['referalid'])) {
						$sql4 = "SELECT * FROM members WHERE MyReferalID = '$referalid' ";
						$result4 = mysqli_query($conn, $sql4);
						$count4 = mysqli_num_rows($result4);
						if ($count4 == 0) {
							echo '<span class="alert alert-danger">Referal ID does not exits!</span>';
						}else{
							//Check if passwords match
							if ($_POST['password'] == $_POST['conpassword']) {
                    			//Hash password
                    			$pwd = password_hash($password, PASSWORD_DEFAULT);
                    			//Insert user to database
                    			$sql5 = "INSERT INTO members (FirstName, LastName, Username, Email, Phone, MyReferalID, ReferalID, RegistrationDate) VALUES ('$firstname', '$lastname', '$username', '$email', '$phone', '$myreferalid', '$referalid', '$date') ";
                    			$result5 = mysqli_query($conn, $sql5) or die(mysqli_error($conn));
                    			if ($result5) {
                    				$sql6 = "INSERT INTO users (Username, Email, Password, Status, Active, RegistrationDate) VALUES ('$username', '$email', '$pwd', 'Member', '1', '$date') ";
                    				$result6 = mysqli_query($conn, $sql6) or die(mysqli_error($conn));
                    				echo '<span class="alert alert-success">Registration Successful!</span>';

                    			}else{
                    				echo '<span class="alert alert-danger">Registration failed!</span>';
                    			}
							}else{
								echo '<span class="alert alert-danger">Passwords do not match!</span>';
							}
						}
					}else{
						//Check if passwords match
						if ($_POST['password'] == $_POST['conpassword']) {
                    		//Hash password
                    		$pwd = password_hash($password, PASSWORD_DEFAULT);
                    		//Insert user to database
                    		$sql5 = "INSERT INTO members (FirstName, LastName, Username, Email, Phone, MyReferalID, ReferalID, RegistrationDate) VALUES ('$firstname', '$lastname', '$username', '$email', '$phone', '$myreferalid', '$referalid', '$date') ";
                    		$result5 = mysqli_query($conn, $sql5) or die(mysqli_error($conn));
                    		if ($result5) {
                    			$sql6 = "INSERT INTO users (Username, Email, Password, Status, Active, RegistrationDate) VALUES ('$username', '$email', '$pwd', 'Member', '1', '$date') ";
                    			$result6 = mysqli_query($conn, $sql6) or die(mysqli_error($conn));
                    			echo '<span class="alert alert-success">Registration Successful!</span>';
                    		}else{
                    			echo '<span class="alert alert-danger">Registration failed!</span>';
                    		}
						}else{
							echo '<span class="alert alert-danger">Passwords do not match!</span>';
						}
					}
				}else{
					echo '<span class="alert alert-danger">Phone already exits!</span>';
				}
			}else{
				echo '<span class="alert alert-danger">Email already exits!</span>';
			}
		}else{
			echo '<span class="alert alert-danger">Username already exits!</span>';
		}
	}else{
		echo '<span class="alert alert-danger">First Name and Last Name pair already exits!</span>';
	}


}
?>