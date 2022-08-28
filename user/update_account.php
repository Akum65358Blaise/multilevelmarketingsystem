<?php
session_start();
if (!$_SESSION['name_member']) {
    header('location: /SecurePrimeInvestment/index.php');
}
if (isset($_POST['submit'])) {
    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    //Get form data
    $password0 = mysqli_real_escape_string($conn, $_POST['password0']);
    $password1 = mysqli_real_escape_string($conn, $_POST['password1']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);
    $username = $_SESSION['name_member'];

    //Other data
    $date = date("Y-m-d H:i:s");

    $sql0 = "SELECT * FROM users WHERE Username = '$username' ";
    $result0 = mysqli_query($conn, $sql0);
    $row0 = mysqli_fetch_assoc($result0);
    $check = password_verify($_POST['password0'], $row0['Password']);
    if ($check == true) {
        if ($_POST['password1'] == $_POST['password2']) {
            $pwd = password_hash($password1, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET Password = '$pwd' WHERE Username = '$username' ";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            if ($result) {
            	?>
            		<script>
                		window.alert('Account password successfully updated!');
                		window.history.back();
            		</script>
        		<?php

            }else{
            	?>
            		<script>
                		window.alert('Failed to change account password. Please try again!');
                		window.history.back();
            		</script>
        		<?php
            }
        }else{
        	?>
            	<script>
                	window.alert('New passwords don not match. Please try again!');
                	window.history.back();
            	</script>
        	<?php
        }
    }else{
    	?>
            <script>
                window.alert('Incorrect current password. Please try again!');
                window.history.back();
            </script>
        <?php
    }

    
}
?>